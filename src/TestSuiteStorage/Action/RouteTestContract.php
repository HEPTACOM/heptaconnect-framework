<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Action;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\RouteKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Create\RouteCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Create\RouteCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Create\RouteCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Create\RouteCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Create\RouteCreateResults;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Find\RouteFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Find\RouteFindCriteria;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Find\RouteFindResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Get\RouteGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Get\RouteGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Get\RouteGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Listing\ReceptionRouteListActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Listing\ReceptionRouteListCriteria;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Listing\ReceptionRouteListResult;
use Heptacom\HeptaConnect\Storage\Base\Enum\RouteCapability;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityB;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityC;
use Heptacom\HeptaConnect\TestSuite\Storage\TestCase;

abstract class RouteTestContract extends TestCase
{
    public function testRouteLifecycle(): void
    {
        $portalA = $this->getPortalNodeA();
        $portalB = $this->getPortalNodeB();
        $createAction = $this->createRouteCreateAction();
        $receptionListAction = $this->createReceptionRouteListAction();
        $findAction = $this->createRouteFindAction();
        $getAction = $this->createRouteGetAction();

        $createPayloads = new RouteCreatePayloads([
            new RouteCreatePayload($portalA, $portalB, EntityA::class),
            new RouteCreatePayload($portalA, $portalB, EntityB::class),
            new RouteCreatePayload($portalA, $portalB, EntityC::class),
            new RouteCreatePayload($portalA, $portalA, EntityA::class),
            new RouteCreatePayload($portalA, $portalA, EntityB::class),
            new RouteCreatePayload($portalA, $portalA, EntityC::class),
            new RouteCreatePayload($portalB, $portalA, EntityA::class),
            new RouteCreatePayload($portalB, $portalA, EntityB::class),
            new RouteCreatePayload($portalB, $portalA, EntityC::class),
            new RouteCreatePayload($portalB, $portalB, EntityA::class),
            new RouteCreatePayload($portalB, $portalB, EntityB::class),
            new RouteCreatePayload($portalB, $portalB, EntityC::class),
            new RouteCreatePayload($portalA, $portalB, EntityA::class, RouteCapability::ALL),
            new RouteCreatePayload($portalA, $portalB, EntityB::class, RouteCapability::ALL),
            new RouteCreatePayload($portalA, $portalB, EntityC::class, RouteCapability::ALL),
            new RouteCreatePayload($portalA, $portalA, EntityA::class, RouteCapability::ALL),
            new RouteCreatePayload($portalA, $portalA, EntityB::class, RouteCapability::ALL),
            new RouteCreatePayload($portalA, $portalA, EntityC::class, RouteCapability::ALL),
            new RouteCreatePayload($portalB, $portalA, EntityA::class, RouteCapability::ALL),
            new RouteCreatePayload($portalB, $portalA, EntityB::class, RouteCapability::ALL),
            new RouteCreatePayload($portalB, $portalA, EntityC::class, RouteCapability::ALL),
            new RouteCreatePayload($portalB, $portalB, EntityA::class, RouteCapability::ALL),
            new RouteCreatePayload($portalB, $portalB, EntityB::class, RouteCapability::ALL),
            new RouteCreatePayload($portalB, $portalB, EntityC::class, RouteCapability::ALL),
        ]);

        $createResults = $createAction->create($createPayloads);

        self::assertSame($createPayloads->count(), $createResults->count());

        $testCreateResults = new RouteCreateResults($createResults);

        /** @var RouteCreatePayload $createPayload */
        foreach ($createPayloads as $createPayload) {
            $findCriteria = new RouteFindCriteria($createPayload->getSourcePortalNodeKey(), $createPayload->getTargetPortalNodeKey(), $createPayload->getEntityType());
            $findResult = $findAction->find($findCriteria);

            self::assertNotNull($findResult);
            /* @var RouteFindResult $findResult */

            self::assertCount(1, \iterable_to_array($testCreateResults->filter(
                static fn (RouteCreateResult $r): bool => $r->getRouteKey()->equals($findResult->getRouteKey())
            )));
            $testCreateResults = new RouteCreateResults($testCreateResults->filter(
                static fn (RouteCreateResult $r): bool => !$r->getRouteKey()->equals($findResult->getRouteKey())
            ));
            /** @var RouteGetResult[] $getResults */
            $getResults = \iterable_to_array($getAction->get(new RouteGetCriteria(new RouteKeyCollection([$findResult->getRouteKey()]))));

            self::assertCount(1, $getResults);

            foreach ($getResults as $getResult) {
                self::assertTrue($getResult->getRouteKey()->equals($findResult->getRouteKey()));
                self::assertTrue($getResult->getSourcePortalNodeKey()->equals($createPayload->getSourcePortalNodeKey()));
                self::assertTrue($getResult->getTargetPortalNodeKey()->equals($createPayload->getTargetPortalNodeKey()));
                self::assertSame($getResult->getEntityType(), $createPayload->getEntityType());

                /** @var ReceptionRouteListResult[] $listResults */
                $listResults = \iterable_to_array($receptionListAction->list(new ReceptionRouteListCriteria($createPayload->getSourcePortalNodeKey(), $createPayload->getEntityType())));
                $receptionListResult = \array_filter(
                    $listResults,
                    static fn(ReceptionRouteListResult $r): bool => $r->getRouteKey()->equals($findResult->getRouteKey())
                );
                $isReceptionRoute = \in_array(RouteCapability::RECEPTION, $getResult->getCapabilities());
                self::assertCount($isReceptionRoute ? 1 : 0, $receptionListResult);
            }
        }
    }

    protected abstract function createRouteCreateAction(): RouteCreateActionInterface;

    protected abstract function createRouteFindAction(): RouteFindActionInterface;

    protected abstract function createRouteGetAction(): RouteGetActionInterface;

    protected abstract function createReceptionRouteListAction(): ReceptionRouteListActionInterface;

    protected abstract function getPortalNodeA(): PortalNodeKeyInterface;

    protected abstract function getPortalNodeB(): PortalNodeKeyInterface;
}
