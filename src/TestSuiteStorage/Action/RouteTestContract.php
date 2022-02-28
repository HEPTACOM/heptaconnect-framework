<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Action;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\RouteKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Delete\RouteDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Find\RouteFindCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Find\RouteFindResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListResult;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\ReceptionRouteListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Enum\RouteCapability;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityB;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityC;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalB\PortalB;
use Heptacom\HeptaConnect\TestSuite\Storage\TestCase;

/**
 * Test pre-implementation to test route related storage actions. Some other storage actions e.g. PortalNodeCreate are needed to set up test scenarios.
 */
abstract class RouteTestContract extends TestCase
{
    private PortalNodeCreateActionInterface $portalNodeCreateAction;

    private PortalNodeDeleteActionInterface $portalNodeDeleteAction;

    private RouteCreateActionInterface $routeCreateAction;

    private ReceptionRouteListActionInterface $routeReceptionListAction;

    private RouteFindActionInterface $routeFindAction;

    private RouteGetActionInterface $routeGetAction;

    private RouteDeleteActionInterface $routeDeleteAction;

    private PortalNodeKeyInterface $portalA;

    private PortalNodeKeyInterface $portalB;

    protected function setUp(): void
    {
        parent::setUp();

        $facade = $this->createStorageFacade();
        $this->portalNodeCreateAction = $facade->getPortalNodeCreateAction();
        $this->portalNodeDeleteAction = $facade->getPortalNodeDeleteAction();
        $this->routeCreateAction = $facade->getRouteCreateAction();
        $this->routeReceptionListAction = $facade->getReceptionRouteListAction();
        $this->routeFindAction = $facade->getRouteFindAction();
        $this->routeGetAction = $facade->getRouteGetAction();
        $this->routeDeleteAction = $facade->getRouteDeleteAction();

        $portalNodeCreateResult = $this->portalNodeCreateAction->create(new PortalNodeCreatePayloads([
            new PortalNodeCreatePayload(PortalA::class),
            new PortalNodeCreatePayload(PortalB::class),
        ]));
        $firstResult = $portalNodeCreateResult->first();
        $lastResult = $portalNodeCreateResult->last();

        static::assertInstanceOf(PortalNodeCreateResult::class, $firstResult);
        static::assertInstanceOf(PortalNodeCreateResult::class, $lastResult);
        static::assertNotSame($firstResult, $lastResult);

        $this->portalA = $firstResult->getPortalNodeKey();
        $this->portalB = $lastResult->getPortalNodeKey();
    }

    /**
     * Validates a complete route "lifecycle" can be managed with the storage. It covers creation, usage, configuration and deletion of routes.
     */
    public function testRouteLifecycle(): void
    {
        $createPayloads = new RouteCreatePayloads();

        foreach ([$this->portalA, $this->portalB] as $sourcePortal) {
            foreach ([$this->portalA, $this->portalB] as $targetPortal) {
                foreach ([EntityA::class, EntityB::class, EntityC::class] as $entityType) {
                    $createPayloads->push([new RouteCreatePayload($sourcePortal, $targetPortal, $entityType)]);
                }
            }
        }

        $createResults = $this->routeCreateAction->create($createPayloads);
        static::assertCount($createPayloads->count(), $createResults);

        /** @var RouteCreatePayload $createPayload */
        foreach ($createPayloads as $createPayload) {
            $findCriteria = new RouteFindCriteria(
                $createPayload->getSourcePortalNodeKey(),
                $createPayload->getTargetPortalNodeKey(),
                $createPayload->getEntityType()
            );

            $findResult = $this->routeFindAction->find($findCriteria);

            static::assertNotNull($findResult);
            /* @var RouteFindResult $findResult */

            static::assertCount(1, \iterable_to_array($createResults->filter(
                static fn (RouteCreateResult $r): bool => $r->getRouteKey()->equals($findResult->getRouteKey())
            )));

            $routeGetCriteria = new RouteGetCriteria(new RouteKeyCollection([$findResult->getRouteKey()]));
            /** @var RouteGetResult[] $getResults */
            $getResults = \iterable_to_array($this->routeGetAction->get($routeGetCriteria));

            static::assertCount(1, $getResults);

            foreach ($getResults as $getResult) {
                static::assertTrue($getResult->getRouteKey()->equals($findResult->getRouteKey()));
                static::assertTrue($getResult->getSourcePortalNodeKey()->equals($createPayload->getSourcePortalNodeKey()));
                static::assertTrue($getResult->getTargetPortalNodeKey()->equals($createPayload->getTargetPortalNodeKey()));
                static::assertSame($getResult->getEntityType(), $createPayload->getEntityType());

                /** @var ReceptionRouteListResult[] $listResults */
                $listResults = \iterable_to_array($this->routeReceptionListAction->list(new ReceptionRouteListCriteria(
                    $createPayload->getSourcePortalNodeKey(),
                    $createPayload->getEntityType()
                )));
                $receptionListResult = \array_filter(
                    $listResults,
                    static fn (ReceptionRouteListResult $r): bool => $r->getRouteKey()->equals($findResult->getRouteKey())
                );
                static::assertCount(0, $receptionListResult);
            }

            $this->routeDeleteAction->delete(new RouteDeleteCriteria($routeGetCriteria->getRouteKeys()));

            static::assertEmpty(\iterable_to_array($this->routeGetAction->get($routeGetCriteria)));

            try {
                $this->routeDeleteAction->delete(new RouteDeleteCriteria($routeGetCriteria->getRouteKeys()));
                static::fail('This should have been throwing a not found exception');
            } catch (NotFoundException $exception) {
            }
        }

        $createPayloads = new RouteCreatePayloads();

        foreach ([$this->portalA, $this->portalB] as $sourcePortal) {
            foreach ([$this->portalA, $this->portalB] as $targetPortal) {
                foreach ([EntityA::class, EntityB::class, EntityC::class] as $entityType) {
                    $createPayloads->push([new RouteCreatePayload($sourcePortal, $targetPortal, $entityType, [
                        RouteCapability::RECEPTION,
                    ])]);
                }
            }
        }

        $createResults = $this->routeCreateAction->create($createPayloads);
        static::assertCount($createPayloads->count(), $createResults);

        $routeKeys = new RouteKeyCollection();

        /** @var RouteCreatePayload $createPayload */
        foreach ($createPayloads as $createPayload) {
            $findCriteria = new RouteFindCriteria(
                $createPayload->getSourcePortalNodeKey(),
                $createPayload->getTargetPortalNodeKey(),
                $createPayload->getEntityType()
            );

            $findResult = $this->routeFindAction->find($findCriteria);

            static::assertNotNull($findResult);
            /* @var RouteFindResult $findResult */

            /** @var ReceptionRouteListResult[] $listResults */
            $listResults = \iterable_to_array($this->routeReceptionListAction->list(new ReceptionRouteListCriteria(
                $createPayload->getSourcePortalNodeKey(),
                $createPayload->getEntityType()
            )));
            $receptionListResult = \array_filter(
                $listResults,
                static fn (ReceptionRouteListResult $r): bool => $r->getRouteKey()->equals($findResult->getRouteKey())
            );
            static::assertCount(1, $receptionListResult);

            $routeKeys->push([$findResult->getRouteKey()]);
        }

        $this->routeDeleteAction->delete(new RouteDeleteCriteria($routeKeys));

        $this->portalNodeDeleteAction->delete(new PortalNodeDeleteCriteria(new PortalNodeKeyCollection([
            $this->portalA,
            $this->portalB,
        ])));
    }

    public function testRouteLifecycleWithDeletedPortalNodes(): void
    {
        $createPayloads = new RouteCreatePayloads();

        foreach ([$this->portalA, $this->portalB] as $sourcePortal) {
            foreach ([$this->portalA, $this->portalB] as $targetPortal) {
                foreach ([EntityA::class, EntityB::class, EntityC::class] as $entityType) {
                    $createPayloads->push([new RouteCreatePayload($sourcePortal, $targetPortal, $entityType, [
                        RouteCapability::RECEPTION,
                    ])]);
                }
            }
        }

        $createResults = $this->routeCreateAction->create($createPayloads);

        static::assertCount($createPayloads->count(), $createResults);

        $this->portalNodeDeleteAction->delete(new PortalNodeDeleteCriteria(new PortalNodeKeyCollection([
            $this->portalA,
            $this->portalB,
        ])));

        foreach ($createPayloads as $createPayload) {
            $findCriteria = new RouteFindCriteria(
                $createPayload->getSourcePortalNodeKey(),
                $createPayload->getTargetPortalNodeKey(),
                $createPayload->getEntityType()
            );

            $findResult = $this->routeFindAction->find($findCriteria);

            static::assertNull($findResult);

            $listResults = \iterable_to_array($this->routeReceptionListAction->list(new ReceptionRouteListCriteria(
                $createPayload->getSourcePortalNodeKey(),
                $createPayload->getEntityType()
            )));

            static::assertCount(0, $listResults);
        }
    }

    /**
     * Provides the storage implementation to test against.
     */
    abstract protected function createStorageFacade(): StorageFacadeInterface;
}
