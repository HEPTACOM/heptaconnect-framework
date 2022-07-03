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
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Delete\JobDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Fail\JobFailPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Finish\JobFinishPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Get\JobGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Schedule\JobSchedulePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Job\Start\JobStartPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\Storage\Base\JobKeyCollection;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA;
use Heptacom\HeptaConnect\TestSuite\Storage\TestCase;

/**
 * Test pre-implementation to test job related storage actions. Some other storage actions e.g. PortalNodeCreate are needed to set up test scenarios.
 */
abstract class JobTestContract extends TestCase
{
    private const MESSAGE = 'This is testing for testing';

    /**
     * Validates a complete job "lifecycle" can be managed with the storage. It covers creation, state changes and deletion of jobs.
     */
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

        $firstPortalNode = $portalNodeCreate->create(new PortalNodeCreatePayloads([
            new PortalNodeCreatePayload(PortalA::class),
        ]))->first();

        static::assertInstanceOf(PortalNodeCreateResult::class, $firstPortalNode);

        $portalNodeKey = $firstPortalNode->getPortalNodeKey();

        $firstRouteCreate = $routeCreate->create(new RouteCreatePayloads([
            new RouteCreatePayload($portalNodeKey, $portalNodeKey, EntityA::class()),
        ]))->first();

        static::assertInstanceOf(RouteCreateResult::class, $firstRouteCreate);

        $routeKey = $firstRouteCreate->getRouteKey();

        $primaryKey = 'd0662bd666c74a56a0e3329b0a32b14a';
        $entity = new EntityA();
        $entity->setPrimaryKey($primaryKey);
        $entity->value = '366b3b50ab9c477ca6189a5c0589c75a';
        $mapping = new MappingComponentStruct($portalNodeKey, EntityA::class(), $primaryKey);

        $jobCreateResults = $jobCreate->create(new JobCreatePayloads([
            new JobCreatePayload(Exploration::class, $mapping, []),
            new JobCreatePayload(Emission::class, $mapping, []),
            new JobCreatePayload(Reception::class, $mapping, [
                Reception::ENTITY => $entity,
                Reception::ROUTE_KEY => $routeKey,
            ]),
        ]));

        $jobKeys = new JobKeyCollection();

        foreach ($jobCreateResults as $jobCreateResult) {
            $jobKeys->push([$jobCreateResult->getJobKey()]);
        }

        static::assertCount($jobKeys->count(), \iterable_to_array($jobGet->get(new JobGetCriteria($jobKeys))));
        static::assertCount(0, \iterable_to_array($jobListFinished->list()));

        $jobStart->start(new JobStartPayload($jobKeys, new \DateTimeImmutable(), self::MESSAGE));

        static::assertCount($jobKeys->count(), \iterable_to_array($jobGet->get(new JobGetCriteria($jobKeys))));
        static::assertCount(0, \iterable_to_array($jobListFinished->list()));

        $jobFail->fail(new JobFailPayload($jobKeys, new \DateTimeImmutable(), self::MESSAGE));

        static::assertCount($jobKeys->count(), \iterable_to_array($jobGet->get(new JobGetCriteria($jobKeys))));
        static::assertCount(0, \iterable_to_array($jobListFinished->list()));

        $jobSchedule->schedule(new JobSchedulePayload($jobKeys, new \DateTimeImmutable(), self::MESSAGE));

        static::assertCount($jobKeys->count(), \iterable_to_array($jobGet->get(new JobGetCriteria($jobKeys))));
        static::assertCount(0, \iterable_to_array($jobListFinished->list()));

        $jobStart->start(new JobStartPayload($jobKeys, new \DateTimeImmutable(), self::MESSAGE));

        static::assertCount($jobKeys->count(), \iterable_to_array($jobGet->get(new JobGetCriteria($jobKeys))));
        static::assertCount(0, \iterable_to_array($jobListFinished->list()));

        $jobFinish->finish(new JobFinishPayload($jobKeys, new \DateTimeImmutable(), self::MESSAGE));

        static::assertCount($jobKeys->count(), \iterable_to_array($jobGet->get(new JobGetCriteria($jobKeys))));
        static::assertCount($jobKeys->count(), \iterable_to_array($jobListFinished->list()));

        $jobDelete->delete(new JobDeleteCriteria($jobKeys));

        static::assertCount(0, \iterable_to_array($jobGet->get(new JobGetCriteria($jobKeys))));
        static::assertCount(0, \iterable_to_array($jobListFinished->list()));

        try {
            $jobDelete->delete(new JobDeleteCriteria($jobKeys));
            static::fail('These jobs are already deleted');
        } catch (\Throwable $throwable) {
        }

        $portalNodeDelete->delete(new PortalNodeDeleteCriteria(new PortalNodeKeyCollection([$portalNodeKey])));
    }

    /**
     * Provides the storage implementation to test against.
     */
    abstract protected function createStorageFacade(): StorageFacadeInterface;
}
