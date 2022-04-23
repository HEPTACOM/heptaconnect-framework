<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalEntityListUi;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeEntityListUiActionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalEntityListUi
 * @covers \Heptacom\HeptaConnect\Core\Portal\FlowComponentRegistry
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListCriteria
 */
final class PortalEntityListUiTest extends TestCase
{
    /**
     * @dataProvider provideGoodFilters
     */
    public function testCriteriaFilters(PortalEntityListCriteria $criteria): void
    {
        $portalNodeEntityListUiAction = $this->createMock(PortalNodeEntityListUiActionInterface::class);

        /** @var PortalNodeEntityListCriteria $passedCriteria */
        $passedCriteria = null;
        $portalNodeEntityListUiAction->method('list')->willReturnCallback(static function ($c) use (&$passedCriteria) {
            $passedCriteria = $c;

            return [];
        });

        $action = new PortalEntityListUi($portalNodeEntityListUiAction);

        \iterable_to_array($action->list($criteria));

        self::assertSame($criteria->getShowExplorer(), $passedCriteria->getShowExplorer());
        self::assertSame($criteria->getShowEmitter(), $passedCriteria->getShowEmitter());
        self::assertSame($criteria->getShowReceiver(), $passedCriteria->getShowReceiver());
        self::assertSame($criteria->getFilterSupportedEntityType(), $passedCriteria->getFilterSupportedEntityType());
    }

    public function testDeleteFakePortalAlsoOnError(): void
    {
        $portalNodeEntityListUiAction = $this->createMock(PortalNodeEntityListUiActionInterface::class);
        $portalNodeEntityListUiAction->method('list')->willThrowException(new \LogicException('My exception'));

        $action = new PortalEntityListUi($portalNodeEntityListUiAction);

        self::expectException(\LogicException::class);

        \iterable_to_array($action->list(new PortalEntityListCriteria(FooBarPortal::class)));
    }

    public function provideGoodFilters(): iterable
    {
        $criteria = new PortalEntityListCriteria(FooBarPortal::class);

        yield [$criteria];

        $criteria = new PortalEntityListCriteria(FooBarPortal::class);
        $criteria->setShowEmitter(false);
        $criteria->setShowExplorer(false);
        $criteria->setShowReceiver(true);

        yield [$criteria];

        $criteria = new PortalEntityListCriteria(FooBarPortal::class);
        $criteria->setShowEmitter(false);
        $criteria->setShowExplorer(true);
        $criteria->setShowReceiver(false);

        yield [$criteria];

        $criteria = new PortalEntityListCriteria(FooBarPortal::class);
        $criteria->setShowEmitter(true);
        $criteria->setShowExplorer(false);
        $criteria->setShowReceiver(false);

        yield [$criteria];

        $criteria = new PortalEntityListCriteria(FooBarPortal::class);
        $criteria->setShowEmitter(true);
        $criteria->setShowExplorer(true);
        $criteria->setShowReceiver(false);

        yield [$criteria];

        $criteria = new PortalEntityListCriteria(FooBarPortal::class);
        $criteria->setFilterSupportedEntityType(FooBarEntity::class);

        yield [$criteria];

        $criteria = new PortalEntityListCriteria(FooBarPortal::class);
        $criteria->setFilterSupportedEntityType(DatasetEntityContract::class);

        yield [$criteria];
    }
}
