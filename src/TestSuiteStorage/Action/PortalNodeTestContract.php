<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Action;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Overview\PortalNodeOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalB\PortalB;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalC\PortalC;
use Heptacom\HeptaConnect\TestSuite\Storage\TestCase;

abstract class PortalNodeTestContract extends TestCase
{
    public function testLifecycle(): void
    {
        $facade = $this->createStorageFacade();
        $createAction = $facade->getPortalNodeCreateAction();
        $deleteAction = $facade->getPortalNodeDeleteAction();
        $getAction = $facade->getPortalNodeGetAction();
        $listAction = $facade->getPortalNodeListAction();
        $overviewAction = $facade->getPortalNodeOverviewAction();

        $createPayloads = new PortalNodeCreatePayloads([
            new PortalNodeCreatePayload(PortalA::class),
            new PortalNodeCreatePayload(PortalA::class),
            new PortalNodeCreatePayload(PortalB::class),
            new PortalNodeCreatePayload(PortalC::class),
        ]);
        $createResults = $createAction->create($createPayloads);

        static::assertSame($createPayloads->count(), $createResults->count());

        $getCriteria = new PortalNodeGetCriteria(new PortalNodeKeyCollection());
        $collectedPortalNodeClasses = [];

        foreach ($createResults as $createResult) {
            $getCriteria->getPortalNodeKeys()->push([$createResult->getPortalNodeKey()]);
            /** @var PortalNodeGetResult[] $getResult */
            $getResult = \iterable_to_array($getAction->get(new PortalNodeGetCriteria(new PortalNodeKeyCollection([$createResult->getPortalNodeKey()]))));
            $collectedPortalNodeClasses[] = $getResult[0]->getPortalClass();

            static::assertCount(1, $getResult);
            static::assertTrue($getResult[0]->getPortalNodeKey()->equals($createResult->getPortalNodeKey()));
        }

        \sort($collectedPortalNodeClasses);
        static::assertSame([
            PortalA::class,
            PortalA::class,
            PortalB::class,
            PortalC::class,
        ], $collectedPortalNodeClasses);

        $deleteCriteria = new PortalNodeDeleteCriteria(new PortalNodeKeyCollection());
        $collectedPortalNodeClasses = [];

        foreach ($listAction->list() as $listResult) {
            $deleteCriteria->getPortalNodeKeys()->push([$listResult->getPortalNodeKey()]);
            /** @var PortalNodeGetResult[] $getResult */
            $getResult = \iterable_to_array($getAction->get(new PortalNodeGetCriteria(new PortalNodeKeyCollection([$listResult->getPortalNodeKey()]))));
            $collectedPortalNodeClasses[] = $getResult[0]->getPortalClass();

            static::assertCount(1, $getResult);
            static::assertTrue($getResult[0]->getPortalNodeKey()->equals($listResult->getPortalNodeKey()));
        }

        \sort($collectedPortalNodeClasses);
        static::assertSame([
            PortalA::class,
            PortalA::class,
            PortalB::class,
            PortalC::class,
        ], $collectedPortalNodeClasses);

        $deleteAction->delete($deleteCriteria);

        static::assertEmpty(\iterable_to_array($listAction->list()));
        static::assertEmpty(\iterable_to_array($overviewAction->overview(new PortalNodeOverviewCriteria())));

        try {
            $deleteAction->delete($deleteCriteria);
        } catch (NotFoundException $notFoundException) {
            return;
        }

        static::fail('You cannot delete something that does not exist');
    }

    abstract protected function createStorageFacade(): StorageFacadeInterface;
}
