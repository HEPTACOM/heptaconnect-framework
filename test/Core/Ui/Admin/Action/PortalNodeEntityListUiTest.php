<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Portal\FlowComponentRegistry;
use Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerFactory;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEmitter;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarExplorer;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarReceiver;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeEntityListUi;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListCriteria;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeEntityListUi
 * @covers \Heptacom\HeptaConnect\Core\Portal\FlowComponentRegistry
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListResult
 */
final class PortalNodeEntityListUiTest extends TestCase
{
    public function testCriteriaFilters(): void
    {
        $portalStackServiceContainerFactory = $this->createMock(PortalStackServiceContainerFactory::class);
        $container = $this->createMock(ContainerInterface::class);

        $flowComponentRegistry = new FlowComponentRegistry(
            [
                FooBarPortal::class => [
                    new FooBarExplorer(5),
                ],
            ],
            [
                FooBarPortal::class => [
                    new FooBarEmitter(5),
                ],
            ],
            [
                FooBarPortal::class => [
                    new FooBarReceiver(),
                ],
            ],
            [
                FooBarPortal::class => [],
            ],
            [
                FooBarPortal::class => [],
            ],
            []
        );

        $container->method('get')->willReturnCallback(static fn (string $id) => [
            FlowComponentRegistry::class => $flowComponentRegistry,
        ][$id]);
        $portalStackServiceContainerFactory->method('create')->willReturn($container);

        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);

        $action = new PortalNodeEntityListUi(
            $portalStackServiceContainerFactory,
            $explorerCodeOriginFinder,
            $emitterCodeOriginFinder,
            $receiverCodeOriginFinder
        );

        $emitterCodeOriginFinder->expects(static::once())->method('findOrigin');
        $explorerCodeOriginFinder->expects(static::once())->method('findOrigin');
        $receiverCodeOriginFinder->expects(static::once())->method('findOrigin');

        $criteria = new PortalNodeEntityListCriteria(new PreviewPortalNodeKey(FooBarPortal::class));
        static::assertCount(3, \iterable_to_array($action->list($criteria)));

        // reset

        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);

        $action = new PortalNodeEntityListUi(
            $portalStackServiceContainerFactory,
            $explorerCodeOriginFinder,
            $emitterCodeOriginFinder,
            $receiverCodeOriginFinder
        );

        $emitterCodeOriginFinder->expects(static::never())->method('findOrigin');
        $explorerCodeOriginFinder->expects(static::never())->method('findOrigin');
        $receiverCodeOriginFinder->expects(static::once())->method('findOrigin');

        $criteria = new PortalNodeEntityListCriteria(new PreviewPortalNodeKey(FooBarPortal::class));
        $criteria->setShowEmitter(false);
        $criteria->setShowExplorer(false);
        $criteria->setShowReceiver(true);
        static::assertCount(1, \iterable_to_array($action->list($criteria)));

        // reset

        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);

        $action = new PortalNodeEntityListUi(
            $portalStackServiceContainerFactory,
            $explorerCodeOriginFinder,
            $emitterCodeOriginFinder,
            $receiverCodeOriginFinder
        );

        $emitterCodeOriginFinder->expects(static::never())->method('findOrigin');
        $explorerCodeOriginFinder->expects(static::once())->method('findOrigin');
        $receiverCodeOriginFinder->expects(static::never())->method('findOrigin');

        $criteria = new PortalNodeEntityListCriteria(new PreviewPortalNodeKey(FooBarPortal::class));
        $criteria->setShowEmitter(false);
        $criteria->setShowExplorer(true);
        $criteria->setShowReceiver(false);
        static::assertCount(1, \iterable_to_array($action->list($criteria)));

        // reset

        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);

        $action = new PortalNodeEntityListUi(
            $portalStackServiceContainerFactory,
            $explorerCodeOriginFinder,
            $emitterCodeOriginFinder,
            $receiverCodeOriginFinder
        );

        $emitterCodeOriginFinder->expects(static::once())->method('findOrigin');
        $explorerCodeOriginFinder->expects(static::never())->method('findOrigin');
        $receiverCodeOriginFinder->expects(static::never())->method('findOrigin');

        $criteria = new PortalNodeEntityListCriteria(new PreviewPortalNodeKey(FooBarPortal::class));
        $criteria->setShowEmitter(true);
        $criteria->setShowExplorer(false);
        $criteria->setShowReceiver(false);
        static::assertCount(1, \iterable_to_array($action->list($criteria)));

        // reset

        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);

        $action = new PortalNodeEntityListUi(
            $portalStackServiceContainerFactory,
            $explorerCodeOriginFinder,
            $emitterCodeOriginFinder,
            $receiverCodeOriginFinder
        );

        $emitterCodeOriginFinder->expects(static::once())->method('findOrigin');
        $explorerCodeOriginFinder->expects(static::once())->method('findOrigin');
        $receiverCodeOriginFinder->expects(static::never())->method('findOrigin');

        $criteria = new PortalNodeEntityListCriteria(new PreviewPortalNodeKey(FooBarPortal::class));
        $criteria->setShowEmitter(true);
        $criteria->setShowExplorer(true);
        $criteria->setShowReceiver(false);
        static::assertCount(2, \iterable_to_array($action->list($criteria)));

        // reset

        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);

        $action = new PortalNodeEntityListUi(
            $portalStackServiceContainerFactory,
            $explorerCodeOriginFinder,
            $emitterCodeOriginFinder,
            $receiverCodeOriginFinder
        );

        $emitterCodeOriginFinder->expects(static::once())->method('findOrigin');
        $explorerCodeOriginFinder->expects(static::once())->method('findOrigin');
        $receiverCodeOriginFinder->expects(static::once())->method('findOrigin');

        $criteria = new PortalNodeEntityListCriteria(new PreviewPortalNodeKey(FooBarPortal::class));
        $criteria->setFilterSupportedEntityType(FooBarEntity::class);
        static::assertCount(3, \iterable_to_array($action->list($criteria)));

        // reset

        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);

        $action = new PortalNodeEntityListUi(
            $portalStackServiceContainerFactory,
            $explorerCodeOriginFinder,
            $emitterCodeOriginFinder,
            $receiverCodeOriginFinder
        );

        $emitterCodeOriginFinder->expects(static::never())->method('findOrigin');
        $explorerCodeOriginFinder->expects(static::never())->method('findOrigin');
        $receiverCodeOriginFinder->expects(static::never())->method('findOrigin');

        $criteria = new PortalNodeEntityListCriteria(new PreviewPortalNodeKey(FooBarPortal::class));
        $criteria->setFilterSupportedEntityType(DatasetEntityContract::class);
        static::assertCount(0, \iterable_to_array($action->list($criteria)));
    }
}
