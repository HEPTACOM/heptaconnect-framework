<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Portal\FlowComponentRegistry;
use Heptacom\HeptaConnect\Core\Portal\PortalNodeContainerFacade;
use Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerFactory;
use Heptacom\HeptaConnect\Core\Support\HttpMiddlewareCollector;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEmitter;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarExplorer;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarReceiver;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeEntityListUi;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\UnsafeClassString;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\StatusReporterCollection;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListCriteria;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContextFactory
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeEntityListUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail
 * @covers \Heptacom\HeptaConnect\Core\Portal\FlowComponentRegistry
 * @covers \Heptacom\HeptaConnect\Core\Portal\PortalNodeContainerFacade
 * @covers \Heptacom\HeptaConnect\Core\Support\HttpMiddlewareCollector
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\EntityListCriteriaContract
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\EntityListResultContract
 */
final class PortalNodeEntityListUiTest extends TestCase
{
    use UiActionTestTrait;

    public function testCriteriaFilters(): void
    {
        $portalStackServiceContainerFactory = $this->createMock(PortalStackServiceContainerFactory::class);
        $container = $this->createMock(ContainerInterface::class);

        $flowComponentRegistry = new FlowComponentRegistry(
            [
                FooBarPortal::class => new ExplorerCollection([
                    new FooBarExplorer(5),
                ]),
            ],
            [
                FooBarPortal::class => new EmitterCollection([
                    new FooBarEmitter(5),
                ]),
            ],
            [
                FooBarPortal::class => new ReceiverCollection([
                    new FooBarReceiver(),
                ]),
            ],
            [
                FooBarPortal::class => new StatusReporterCollection(),
            ],
            [
                FooBarPortal::class => new HttpHandlerCollection(),
            ],
            []
        );

        $container->method('get')->willReturnCallback(fn (string $id) => [
            FlowComponentRegistry::class => $flowComponentRegistry,
            HttpMiddlewareCollector::class => new HttpMiddlewareCollector([]),
        ][$id] ?? $this->createMock($id));
        $portalStackServiceContainerFactory->method('create')
            ->willReturn(new PortalNodeContainerFacade($container));

        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);

        $action = new PortalNodeEntityListUi(
            $this->createAuditTrailFactory(),
            $portalStackServiceContainerFactory,
            $explorerCodeOriginFinder,
            $emitterCodeOriginFinder,
            $receiverCodeOriginFinder
        );

        $codeOrigin = new CodeOrigin(__FILE__, __LINE__, __LINE__);
        $emitterCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);
        $explorerCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);
        $receiverCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);

        $criteria = new PortalNodeEntityListCriteria(new PreviewPortalNodeKey(FooBarPortal::class()));
        static::assertCount(3, \iterable_to_array($action->list($criteria, $this->createUiActionContext())));

        // reset

        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);

        $action = new PortalNodeEntityListUi(
            $this->createAuditTrailFactory(),
            $portalStackServiceContainerFactory,
            $explorerCodeOriginFinder,
            $emitterCodeOriginFinder,
            $receiverCodeOriginFinder
        );

        $emitterCodeOriginFinder->expects(static::never())->method('findOrigin')->willReturn($codeOrigin);
        $explorerCodeOriginFinder->expects(static::never())->method('findOrigin')->willReturn($codeOrigin);
        $receiverCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);

        $criteria = new PortalNodeEntityListCriteria(new PreviewPortalNodeKey(FooBarPortal::class()));
        $criteria->setShowEmitter(false);
        $criteria->setShowExplorer(false);
        $criteria->setShowReceiver(true);
        static::assertCount(1, \iterable_to_array($action->list($criteria, $this->createUiActionContext())));

        // reset

        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);

        $action = new PortalNodeEntityListUi(
            $this->createAuditTrailFactory(),
            $portalStackServiceContainerFactory,
            $explorerCodeOriginFinder,
            $emitterCodeOriginFinder,
            $receiverCodeOriginFinder
        );

        $emitterCodeOriginFinder->expects(static::never())->method('findOrigin')->willReturn($codeOrigin);
        $explorerCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);
        $receiverCodeOriginFinder->expects(static::never())->method('findOrigin')->willReturn($codeOrigin);

        $criteria = new PortalNodeEntityListCriteria(new PreviewPortalNodeKey(FooBarPortal::class()));
        $criteria->setShowEmitter(false);
        $criteria->setShowExplorer(true);
        $criteria->setShowReceiver(false);
        static::assertCount(1, \iterable_to_array($action->list($criteria, $this->createUiActionContext())));

        // reset

        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);

        $action = new PortalNodeEntityListUi(
            $this->createAuditTrailFactory(),
            $portalStackServiceContainerFactory,
            $explorerCodeOriginFinder,
            $emitterCodeOriginFinder,
            $receiverCodeOriginFinder
        );

        $emitterCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);
        $explorerCodeOriginFinder->expects(static::never())->method('findOrigin')->willReturn($codeOrigin);
        $receiverCodeOriginFinder->expects(static::never())->method('findOrigin')->willReturn($codeOrigin);

        $criteria = new PortalNodeEntityListCriteria(new PreviewPortalNodeKey(FooBarPortal::class()));
        $criteria->setShowEmitter(true);
        $criteria->setShowExplorer(false);
        $criteria->setShowReceiver(false);
        static::assertCount(1, \iterable_to_array($action->list($criteria, $this->createUiActionContext())));

        // reset

        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);

        $action = new PortalNodeEntityListUi(
            $this->createAuditTrailFactory(),
            $portalStackServiceContainerFactory,
            $explorerCodeOriginFinder,
            $emitterCodeOriginFinder,
            $receiverCodeOriginFinder
        );

        $emitterCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);
        $explorerCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);
        $receiverCodeOriginFinder->expects(static::never())->method('findOrigin')->willReturn($codeOrigin);

        $criteria = new PortalNodeEntityListCriteria(new PreviewPortalNodeKey(FooBarPortal::class()));
        $criteria->setShowEmitter(true);
        $criteria->setShowExplorer(true);
        $criteria->setShowReceiver(false);
        static::assertCount(2, \iterable_to_array($action->list($criteria, $this->createUiActionContext())));

        // reset

        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);

        $action = new PortalNodeEntityListUi(
            $this->createAuditTrailFactory(),
            $portalStackServiceContainerFactory,
            $explorerCodeOriginFinder,
            $emitterCodeOriginFinder,
            $receiverCodeOriginFinder
        );

        $emitterCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);
        $explorerCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);
        $receiverCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);

        $criteria = new PortalNodeEntityListCriteria(new PreviewPortalNodeKey(FooBarPortal::class()));
        $criteria->setFilterSupportedEntityType(FooBarEntity::class());
        static::assertCount(3, \iterable_to_array($action->list($criteria, $this->createUiActionContext())));

        // reset

        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);

        $action = new PortalNodeEntityListUi(
            $this->createAuditTrailFactory(),
            $portalStackServiceContainerFactory,
            $explorerCodeOriginFinder,
            $emitterCodeOriginFinder,
            $receiverCodeOriginFinder
        );

        $emitterCodeOriginFinder->expects(static::never())->method('findOrigin')->willReturn($codeOrigin);
        $explorerCodeOriginFinder->expects(static::never())->method('findOrigin')->willReturn($codeOrigin);
        $receiverCodeOriginFinder->expects(static::never())->method('findOrigin')->willReturn($codeOrigin);

        $criteria = new PortalNodeEntityListCriteria(new PreviewPortalNodeKey(FooBarPortal::class()));
        $criteria->setFilterSupportedEntityType(new UnsafeClassString(DatasetEntityContract::class));
        static::assertCount(0, \iterable_to_array($action->list($criteria, $this->createUiActionContext())));
    }
}
