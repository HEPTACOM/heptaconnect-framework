<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Action;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Find\PortalNodeAliasFindCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Get\PortalNodeAliasGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Overview\PortalNodeAliasOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Set\PortalNodeAliasSetPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Set\PortalNodeAliasSetPayloads;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalB\PortalB;
use Heptacom\HeptaConnect\TestSuite\Storage\TestCase;

/**
 * Test pre-implementation to test alias usage of portal node alias related storage actions. Some other storage actions e.g. PortalNodeCreate are needed to set up test scenarios.
 */
abstract class PortalNodeAliasTestContract extends TestCase
{
    /**
     * Validates a complete portal node configuration "lifecycle" can be managed with the storage. It covers read, write, list and deletion of configuration entries.
     */
    public function testLifecycle(): void
    {
        $facade = $this->createStorageFacade();
        $createAction = $facade->getPortalNodeCreateAction();
        $deleteAction = $facade->getPortalNodeDeleteAction();
        $setAlias = $facade->getPortalNodeAliasSetAction();
        $findAlias = $facade->getPortalNodeAliasFindAction();
        $getAlias = $facade->getPortalNodeAliasGetAction();
        $overviewAlias = $facade->getPortalNodeAliasOverviewAction();

        $createPayloads = new PortalNodeCreatePayloads([
            new PortalNodeCreatePayload(PortalA::class()),
            new PortalNodeCreatePayload(PortalB::class()),
        ]);
        $createResults = $createAction->create($createPayloads);
        $portalNodeKeys = new PortalNodeKeyCollection(\iterable_map(
            $createResults,
            static fn (PortalNodeCreateResult $r): PortalNodeKeyInterface => $r->getPortalNodeKey()
        ));

        static::assertCount(2, $createResults);
        $portalA = $createResults[0]->getPortalNodeKey();
        $portalB = $createResults[1]->getPortalNodeKey();

        $setAlias->set(new PortalNodeAliasSetPayloads([
            new PortalNodeAliasSetPayload($portalA, 'portal-a'),
            new PortalNodeAliasSetPayload($portalB, 'portal-b'),
        ]));

        $aliasGetCriteria = new PortalNodeAliasGetCriteria(new PortalNodeKeyCollection([$portalA, $portalB]));

        foreach ($getAlias->get($aliasGetCriteria) as $getResult) {
            if ($getResult->getPortalNodeKey()->equals($portalA)) {
                static::assertSame('portal-a', $getResult->getAlias());
            } elseif ($getResult->getPortalNodeKey()->equals($portalB)) {
                static::assertSame('portal-b', $getResult->getAlias());
            } else {
                static::fail();
            }
        }

        foreach ($findAlias->find(new PortalNodeAliasFindCriteria(['portal-b'])) as $findResult) {
            static::assertTrue($findResult->getPortalNodeKey()->equals($portalB));
        }

        $setAlias->set(new PortalNodeAliasSetPayloads([
            new PortalNodeAliasSetPayload($portalA, null),
            new PortalNodeAliasSetPayload($portalB, null),
        ]));

        static::assertEmpty(\iterable_to_array($getAlias->get($aliasGetCriteria)));

        $setAlias->set(new PortalNodeAliasSetPayloads([
            new PortalNodeAliasSetPayload($portalA, 'portal-a'),
            new PortalNodeAliasSetPayload($portalB, 'portal-b'),
        ]));
        static::assertCount(2, \iterable_to_array($getAlias->get($aliasGetCriteria)));
        static::assertCount(2, \iterable_to_array($findAlias->find(new PortalNodeAliasFindCriteria(['portal-a', 'portal-b']))));
        static::assertCount(2, \iterable_to_array($overviewAlias->overview(new PortalNodeAliasOverviewCriteria())));

        $deleteAction->delete(new PortalNodeDeleteCriteria($portalNodeKeys));

        static::assertEmpty(\iterable_to_array($findAlias->find(new PortalNodeAliasFindCriteria(['portal-a', 'portal-b']))));
        static::assertEmpty(\iterable_to_array($getAlias->get($aliasGetCriteria)));
        static::assertEmpty(\iterable_to_array($overviewAlias->overview(new PortalNodeAliasOverviewCriteria())));
    }

    /**
     * Provides the storage implementation to test against.
     */
    abstract protected function createStorageFacade(): StorageFacadeInterface;
}
