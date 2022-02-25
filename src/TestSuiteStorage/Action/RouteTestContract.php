<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Action;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\RouteKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreateResults;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Delete\RouteDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Find\RouteFindCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Find\RouteFindResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListResult;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityB;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityC;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalB\PortalB;
use Heptacom\HeptaConnect\TestSuite\Storage\TestCase;

abstract class RouteTestContract extends TestCase
{
    public function testRouteLifecycle(): void
    {
        $facade = $this->createStorageFacade();
        $portalNodeCreate = $facade->getPortalNodeCreateAction();
        $portalNodeDelete = $facade->getPortalNodeDeleteAction();
        $createAction = $facade->getRouteCreateAction();
        $receptionListAction = $facade->getReceptionRouteListAction();
        $findAction = $facade->getRouteFindAction();
        $getAction = $facade->getRouteGetAction();
        $deleteAction = $facade->getRouteDeleteAction();

        $portalNodeCreateResult = $portalNodeCreate->create(new PortalNodeCreatePayloads([
            new PortalNodeCreatePayload(PortalA::class),
            new PortalNodeCreatePayload(PortalB::class),
        ]));
        $firstResult = $portalNodeCreateResult->first();
        $lastResult = $portalNodeCreateResult->last();

        static::assertInstanceOf(PortalNodeCreateResult::class, $firstResult);
        static::assertInstanceOf(PortalNodeCreateResult::class, $lastResult);

        $portalA = $firstResult->getPortalNodeKey();
        $portalB = $lastResult->getPortalNodeKey();

        $createPayloads = new RouteCreatePayloads();

        foreach ([$portalA, $portalB] as $sourcePortal) {
            foreach ([$portalA, $portalB] as $targetPortal) {
                foreach ([EntityA::class, EntityB::class, EntityC::class] as $entityType) {
                    $createPayloads->push([new RouteCreatePayload($sourcePortal, $targetPortal, $entityType)]);
                }
            }
        }

        $createResults = $createAction->create($createPayloads);

        static::assertCount($createPayloads->count(), $createResults);

        $testCreateResults = new RouteCreateResults($createResults);

        foreach ($createPayloads as $createPayload) {
            $findCriteria = new RouteFindCriteria($createPayload->getSourcePortalNodeKey(), $createPayload->getTargetPortalNodeKey(), $createPayload->getEntityType());
            $findResult = $findAction->find($findCriteria);

            static::assertNotNull($findResult);
            /* @var RouteFindResult $findResult */

            static::assertCount(1, \iterable_to_array($testCreateResults->filter(
                static fn (RouteCreateResult $r): bool => $r->getRouteKey()->equals($findResult->getRouteKey())
            )));
            $testCreateResults = new RouteCreateResults($testCreateResults->filter(
                static fn (RouteCreateResult $r): bool => !$r->getRouteKey()->equals($findResult->getRouteKey())
            ));
            $routeGetCriteria = new RouteGetCriteria(new RouteKeyCollection([$findResult->getRouteKey()]));
            /** @var RouteGetResult[] $getResults */
            $getResults = \iterable_to_array($getAction->get($routeGetCriteria));

            static::assertCount(1, $getResults);

            foreach ($getResults as $getResult) {
                static::assertTrue($getResult->getRouteKey()->equals($findResult->getRouteKey()));
                static::assertTrue($getResult->getSourcePortalNodeKey()->equals($createPayload->getSourcePortalNodeKey()));
                static::assertTrue($getResult->getTargetPortalNodeKey()->equals($createPayload->getTargetPortalNodeKey()));
                static::assertSame($getResult->getEntityType(), $createPayload->getEntityType());

                /** @var ReceptionRouteListResult[] $listResults */
                $listResults = \iterable_to_array($receptionListAction->list(new ReceptionRouteListCriteria($createPayload->getSourcePortalNodeKey(), $createPayload->getEntityType())));
                $receptionListResult = \array_filter(
                    $listResults,
                    static fn (ReceptionRouteListResult $r): bool => $r->getRouteKey()->equals($findResult->getRouteKey())
                );
                static::assertCount(0, $receptionListResult);
            }

            $deleteAction->delete(new RouteDeleteCriteria($routeGetCriteria->getRouteKeys()));

            static::assertEmpty(\iterable_to_array($getAction->get($routeGetCriteria)));

            try {
                $deleteAction->delete(new RouteDeleteCriteria($routeGetCriteria->getRouteKeys()));
                static::fail('This should have been throwing a not found exception');
            } catch (NotFoundException $exception) {
            }
        }

        $portalNodeDelete->delete(new PortalNodeDeleteCriteria(new PortalNodeKeyCollection([$portalA, $portalB])));
    }

    abstract protected function createStorageFacade(): StorageFacadeInterface;
}
