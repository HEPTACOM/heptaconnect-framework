<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Action;

use Heptacom\HeptaConnect\Core\Job\Type\Emission;
use Heptacom\HeptaConnect\Core\Job\Type\Exploration;
use Heptacom\HeptaConnect\Core\Job\Type\Reception;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Create\JobCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Create\JobCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Create\JobCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Delete\JobDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Fail\JobFailPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Finish\JobFinishPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Schedule\JobSchedulePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Start\JobStartPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA;
use Heptacom\HeptaConnect\TestSuite\Storage\TestCase;

abstract class JobTestContract extends TestCase
{
    private const MESSAGE = 'This is testing for testing';

    public function testLifecycle(): void
    {
        $facade = $this->createStorageFacade();
        $portalNodeCreate = $facade->getPortalNodeCreateAction();
        $portalNodeDelete = $facade->getPortalNodeDeleteAction();
        $routeCreate = $facade->getRouteCreateAction();
        $jobCreate = $facade->getJobCreateAction();
        $jobDelete = $facade->getJobDeleteAction();
        $jobFail = $facade->getJobFailAction();
        $jobFinish = $facade->getJobFinishAction();
        $jobGet = $facade->getJobGetAction();
        $jobListFinished = $facade->getJobListFinishedAction();
        $jobSchedule = $facade->getJobScheduleAction();
        $jobStart = $facade->getJobStartAction();

        $portalNodeKey = $portalNodeCreate->create(new PortalNodeCreatePayloads([
            new PortalNodeCreatePayload(PortalA::class),
        ]))->first()->getPortalNodeKey();
        $routeKey = $routeCreate->create(new RouteCreatePayloads([
            new RouteCreatePayload($portalNodeKey, $portalNodeKey, EntityA::class),
        ]))->first()->getRouteKey();

        $entity = new EntityA();
        $entity->setPrimaryKey('d0662bd666c74a56a0e3329b0a32b14a');
        $entity->value = '366b3b50ab9c477ca6189a5c0589c75a';
        $mapping = new MappingComponentStruct($portalNodeKey, EntityA::class, $entity->getPrimaryKey());

        $jobCreateResults = $jobCreate->create(new JobCreatePayloads([
            new JobCreatePayload(Exploration::class, $mapping, []),
            new JobCreatePayload(Emission::class, $mapping, []),
            new JobCreatePayload(Reception::class, $mapping, [
                Reception::ENTITY => $entity,
                Reception::ROUTE_KEY => $routeKey,
            ]),
        ]));

        $jobKeys = new JobKeyCollection();

        /** @var JobCreateResult $jobCreateResult */
        foreach ($jobCreateResults as $jobCreateResult) {
            $jobKeys->push([$jobCreateResult->getJobKey()]);
        }

        self::assertCount($jobKeys->count(), \iterable_to_array($jobGet->get(new JobGetCriteria($jobKeys))));
        self::assertCount(0, \iterable_to_array($jobListFinished->list()));

        $jobStart->start(new JobStartPayload($jobKeys, new \DateTimeImmutable(), self::MESSAGE));

        self::assertCount($jobKeys->count(), \iterable_to_array($jobGet->get(new JobGetCriteria($jobKeys))));
        self::assertCount(0, \iterable_to_array($jobListFinished->list()));

        $jobFail->fail(new JobFailPayload($jobKeys, new \DateTimeImmutable(), self::MESSAGE));

        self::assertCount($jobKeys->count(), \iterable_to_array($jobGet->get(new JobGetCriteria($jobKeys))));
        self::assertCount(0, \iterable_to_array($jobListFinished->list()));

        $jobSchedule->schedule(new JobSchedulePayload($jobKeys, new \DateTimeImmutable(), self::MESSAGE));

        self::assertCount($jobKeys->count(), \iterable_to_array($jobGet->get(new JobGetCriteria($jobKeys))));
        self::assertCount(0, \iterable_to_array($jobListFinished->list()));

        $jobStart->start(new JobStartPayload($jobKeys, new \DateTimeImmutable(), self::MESSAGE));

        self::assertCount($jobKeys->count(), \iterable_to_array($jobGet->get(new JobGetCriteria($jobKeys))));
        self::assertCount(0, \iterable_to_array($jobListFinished->list()));

        $jobFinish->finish(new JobFinishPayload($jobKeys, new \DateTimeImmutable(), self::MESSAGE));

        self::assertCount($jobKeys->count(), \iterable_to_array($jobListFinished->list()));

        $jobDelete->delete(new JobDeleteCriteria($jobKeys));

        self::assertCount(0, \iterable_to_array($jobGet->get(new JobGetCriteria($jobKeys))));
        self::assertCount(0, \iterable_to_array($jobListFinished->list()));

        try {
            $jobDelete->delete(new JobDeleteCriteria($jobKeys));
            self::fail('These jobs are already delete');
        } catch (\Throwable $throwable) {
        }

        $portalNodeDelete->delete(new PortalNodeDeleteCriteria(new PortalNodeKeyCollection([$portalNodeKey])));
    }

    abstract protected function createStorageFacade(): StorageFacadeInterface;
}
