<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Action;

use Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
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
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Overview\RouteOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Overview\RouteOverviewResult;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\ReceptionRouteListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteOverviewActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Enum\RouteCapability;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\Storage\Base\RouteKeyCollection;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityB;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityC;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalB\PortalB;
use Heptacom\HeptaConnect\TestSuite\Storage\TestCase;

/**
 * Test pre-implementation to test route related storage actions. Some other storage actions e.g. PortalNodeCreate are needed to set up test scenarios.
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
abstract class RouteTestContract extends TestCase
{
    private PortalNodeCreateActionInterface $portalNodeCreateAction;

    private PortalNodeDeleteActionInterface $portalNodeDeleteAction;

    private RouteCreateActionInterface $routeCreateAction;

    private ReceptionRouteListActionInterface $routeReceptionListAction;

    private RouteOverviewActionInterface $routeOverviewAction;

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
        $this->routeOverviewAction = $facade->getRouteOverviewAction();

        $portalNodeCreateResult = $this->portalNodeCreateAction->create(new PortalNodeCreatePayloads([
            new PortalNodeCreatePayload(PortalA::class()),
            new PortalNodeCreatePayload(PortalB::class()),
        ]));
        $firstResult = $portalNodeCreateResult->first();
        $lastResult = $portalNodeCreateResult->last();

        static::assertInstanceOf(PortalNodeCreateResult::class, $firstResult);
        static::assertInstanceOf(PortalNodeCreateResult::class, $lastResult);
        static::assertNotSame($firstResult, $lastResult);

        $this->portalA = $firstResult->getPortalNodeKey();
        $this->portalB = $lastResult->getPortalNodeKey();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        try {
            $this->portalNodeDeleteAction->delete(new PortalNodeDeleteCriteria(new PortalNodeKeyCollection([$this->portalA])));
        } catch (NotFoundException $e) {
        }

        try {
            $this->portalNodeDeleteAction->delete(new PortalNodeDeleteCriteria(new PortalNodeKeyCollection([$this->portalB])));
        } catch (NotFoundException $e) {
        }
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
                    $createPayloads->push([new RouteCreatePayload($sourcePortal, $targetPortal, $entityType::class())]);
                }
            }
        }

        $createResults = $this->routeCreateAction->create($createPayloads);
        static::assertCount($createPayloads->count(), $createResults);

        foreach ($createPayloads as $createPayload) {
            $findCriteria = new RouteFindCriteria(
                $createPayload->getSourcePortalNodeKey(),
                $createPayload->getTargetPortalNodeKey(),
                $createPayload->getEntityType()
            );

            $findResult = $this->routeFindAction->find($findCriteria);

            static::assertNotNull($findResult);
            /* @var RouteFindResult $findResult */

            static::assertCount(1, $createResults->filter(
                static fn (RouteCreateResult $r): bool => $r->getRouteKey()->equals($findResult->getRouteKey())
            ));

            $overviewCriteria = new RouteOverviewCriteria();
            $overviewCriteria->setEntityTypeFilter(new ClassStringReferenceCollection([$createPayload->getEntityType()]));
            $overviewCriteria->setSourcePortalNodeKeyFilter(new PortalNodeKeyCollection([$createPayload->getSourcePortalNodeKey()]));
            $overviewCriteria->setTargetPortalNodeKeyFilter(new PortalNodeKeyCollection([$createPayload->getTargetPortalNodeKey()]));
            $overviewCriteria->setPage(1);
            $overviewCriteria->setPageSize(1);

            $overviewResult = $this->routeOverviewAction->overview($overviewCriteria);

            static::assertCount(1, \iterable_to_array(\iterable_filter(
                $overviewResult,
                static fn (RouteOverviewResult $r): bool => $r->getRouteKey()->equals($findResult->getRouteKey())
            )));

            $overviewAllResult = \iterable_to_array($this->routeOverviewAction->overview(new RouteOverviewCriteria()));
            static::assertCount(1, $overviewAllResult);
            static::assertCount(1, \array_filter(
                $overviewAllResult,
                static fn (RouteOverviewResult $r): bool => $r->getRouteKey()->equals($findResult->getRouteKey())
            ));

            $routeGetCriteria = new RouteGetCriteria(new RouteKeyCollection([$findResult->getRouteKey()]));
            /** @var RouteGetResult[] $getResults */
            $getResults = \iterable_to_array($this->routeGetAction->get($routeGetCriteria));

            static::assertCount(1, $getResults);

            foreach ($getResults as $getResult) {
                static::assertTrue($getResult->getRouteKey()->equals($findResult->getRouteKey()));
                static::assertTrue($getResult->getSourcePortalNodeKey()->equals($createPayload->getSourcePortalNodeKey()));
                static::assertTrue($getResult->getTargetPortalNodeKey()->equals($createPayload->getTargetPortalNodeKey()));
                static::assertTrue($getResult->getEntityType()->equals($createPayload->getEntityType()));

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
            } catch (NotFoundException) {
            }
        }

        $createPayloads = new RouteCreatePayloads();

        foreach ([$this->portalA, $this->portalB] as $sourcePortal) {
            foreach ([$this->portalA, $this->portalB] as $targetPortal) {
                foreach ([EntityA::class, EntityB::class, EntityC::class] as $entityType) {
                    $createPayloads->push([new RouteCreatePayload($sourcePortal, $targetPortal, $entityType::class(), [
                        RouteCapability::RECEPTION,
                    ])]);
                }
            }
        }

        $createResults = $this->routeCreateAction->create($createPayloads);
        static::assertCount($createPayloads->count(), $createResults);

        $routeKeys = new RouteKeyCollection();

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
    }

    /**
     * Validates that deleting portals will also delete routes.
     */
    public function testRouteLifecycleWithDeletedPortalNodes(): void
    {
        $createPayloads = new RouteCreatePayloads();

        foreach ([$this->portalA, $this->portalB] as $sourcePortal) {
            foreach ([$this->portalA, $this->portalB] as $targetPortal) {
                foreach ([EntityA::class, EntityB::class, EntityC::class] as $entityType) {
                    $createPayloads->push([new RouteCreatePayload($sourcePortal, $targetPortal, $entityType::class(), [
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

    public function testDeletedAt(): void
    {
        $routeKeys = $this->setUpOverview();
        $criteria = new RouteOverviewCriteria();
        static::assertCount(4, \iterable_to_array($this->routeOverviewAction->overview($criteria)));
        $this->tearDownOverview($routeKeys);
    }

    public function testPagination(): void
    {
        $routeKeys = $this->setUpOverview();

        $criteria0 = new RouteOverviewCriteria();
        $criteria0->setPageSize(1);
        $criteria0->setPage(0);

        $criteria1 = clone $criteria0;
        $criteria1->setPage(1);

        $criteria2 = clone $criteria0;
        $criteria2->setPage(2);

        $criteria3 = clone $criteria0;
        $criteria3->setPage(3);

        $criteria4 = clone $criteria0;
        $criteria4->setPage(4);

        $criteria5 = clone $criteria0;
        $criteria5->setPage(5);

        static::assertCount(1, \iterable_to_array($this->routeOverviewAction->overview($criteria0)));
        static::assertCount(1, \iterable_to_array($this->routeOverviewAction->overview($criteria1)));
        static::assertCount(1, \iterable_to_array($this->routeOverviewAction->overview($criteria2)));
        static::assertCount(1, \iterable_to_array($this->routeOverviewAction->overview($criteria3)));
        static::assertCount(1, \iterable_to_array($this->routeOverviewAction->overview($criteria4)));
        static::assertCount(0, \iterable_to_array($this->routeOverviewAction->overview($criteria5)));

        $this->tearDownOverview($routeKeys);
    }

    public function testSortByDateAsc(): void
    {
        $routeKeys = $this->setUpOverview();
        $criteria = new RouteOverviewCriteria();
        $criteria->setSort([
            RouteOverviewCriteria::FIELD_CREATED => RouteOverviewCriteria::SORT_ASC,
        ]);

        /** @var RouteOverviewResult $item */
        foreach ($this->routeOverviewAction->overview($criteria) as $item) {
            $this->tearDownOverview($routeKeys);
            static::assertTrue($item->getRouteKey()->equals($routeKeys['routeFirst']));

            break;
        }
    }

    public function testSortByDateDesc(): void
    {
        $routeKeys = $this->setUpOverview();
        $criteria = new RouteOverviewCriteria();
        $criteria->setSort([
            RouteOverviewCriteria::FIELD_CREATED => RouteOverviewCriteria::SORT_DESC,
        ]);

        /** @var RouteOverviewResult $item */
        foreach ($this->routeOverviewAction->overview($criteria) as $item) {
            $this->tearDownOverview($routeKeys);
            static::assertTrue($item->getRouteKey()->equals($routeKeys['routeLast']));

            break;
        }
    }

    public function testSortByEntityTypeAsc(): void
    {
        $routeKeys = $this->setUpOverview();
        $criteria = new RouteOverviewCriteria();
        $criteria->setSort([
            RouteOverviewCriteria::FIELD_ENTITY_TYPE => RouteOverviewCriteria::SORT_ASC,
        ]);

        $indexA = null;
        $indexB = null;

        /** @var RouteOverviewResult $item */
        foreach ($this->routeOverviewAction->overview($criteria) as $index => $item) {
            if ($item->getRouteKey()->equals($routeKeys['routeTypeA'])) {
                $indexA = $index;
            }

            if ($item->getRouteKey()->equals($routeKeys['routeTypeB'])) {
                $indexB = $index;
            }
        }

        $this->tearDownOverview($routeKeys);

        static::assertGreaterThan($indexA, $indexB);
    }

    public function testSortByEntityTypeDesc(): void
    {
        $routeKeys = $this->setUpOverview();
        $criteria = new RouteOverviewCriteria();
        $criteria->setSort([
            RouteOverviewCriteria::FIELD_ENTITY_TYPE => RouteOverviewCriteria::SORT_DESC,
        ]);

        $indexA = null;
        $indexB = null;

        /** @var RouteOverviewResult $item */
        foreach ($this->routeOverviewAction->overview($criteria) as $index => $item) {
            if ($item->getRouteKey()->equals($routeKeys['routeTypeA'])) {
                $indexA = $index;
            }

            if ($item->getRouteKey()->equals($routeKeys['routeTypeB'])) {
                $indexB = $index;
            }
        }

        $this->tearDownOverview($routeKeys);

        static::assertGreaterThan($indexB, $indexA);
    }

    /**
     * Provides the storage implementation to test against.
     */
    abstract protected function createStorageFacade(): StorageFacadeInterface;

    /**
     * @return array{
     *                routeDeleted: RouteKeyInterface,
     *                routeTypeA: RouteKeyInterface,
     *                routeTypeB: RouteKeyInterface,
     *                routeFirst: RouteKeyInterface,
     *                routeLast: RouteKeyInterface
     *                }
     */
    private function setUpOverview(): array
    {
        $routeDeleted = $this->routeCreateAction->create(new RouteCreatePayloads([
            new RouteCreatePayload($this->portalA, $this->portalB, EntityA::class()),
        ]))->first()->getRouteKey();
        $this->routeDeleteAction->delete(new RouteDeleteCriteria(new RouteKeyCollection([$routeDeleted])));

        $routeFirst = $this->routeCreateAction->create(new RouteCreatePayloads([
            new RouteCreatePayload($this->portalA, $this->portalB, EntityA::class()),
        ]))->first()->getRouteKey();

        \sleep(1);

        $routeTypeA = $this->routeCreateAction->create(new RouteCreatePayloads([
            new RouteCreatePayload($this->portalA, $this->portalB, EntityA::class()),
        ]))->first()->getRouteKey();
        $routeTypeB = $this->routeCreateAction->create(new RouteCreatePayloads([
            new RouteCreatePayload($this->portalA, $this->portalB, EntityB::class()),
        ]))->first()->getRouteKey();

        \sleep(1);

        $routeLast = $this->routeCreateAction->create(new RouteCreatePayloads([
            new RouteCreatePayload($this->portalA, $this->portalB, EntityA::class()),
        ]))->first()->getRouteKey();

        return [
            'routeDeleted' => $routeDeleted,
            'routeTypeA' => $routeTypeA,
            'routeTypeB' => $routeTypeB,
            'routeFirst' => $routeFirst,
            'routeLast' => $routeLast,
        ];
    }

    /**
     * @param RouteKeyInterface[] $routeKeys
     */
    private function tearDownOverview(array $routeKeys): void
    {
        unset($routeKeys['routeDeleted']);

        $this->routeDeleteAction->delete(new RouteDeleteCriteria(new RouteKeyCollection(\array_values($routeKeys))));
    }
}
