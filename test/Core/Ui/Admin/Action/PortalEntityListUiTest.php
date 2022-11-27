<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalEntityListUi;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\UnsafeClassString;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\PortalNode\PortalNodeEntityListUiActionInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContextFactory
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalEntityListUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail
 * @covers \Heptacom\HeptaConnect\Core\Portal\FlowComponentRegistry
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Portal\PortalEntityList\PortalEntityListResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeEntityList\PortalNodeEntityListCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\EntityListCriteriaContract
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\EntityListResultContract
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException
 */
final class PortalEntityListUiTest extends TestCase
{
    use UiActionTestTrait;

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

        $action = new PortalEntityListUi($this->createAuditTrailFactory(), $portalNodeEntityListUiAction);

        \iterable_to_array($action->list($criteria, $this->createUiActionContext()));

        static::assertSame($criteria->getShowExplorer(), $passedCriteria->getShowExplorer());
        static::assertSame($criteria->getShowEmitter(), $passedCriteria->getShowEmitter());
        static::assertSame($criteria->getShowReceiver(), $passedCriteria->getShowReceiver());
        
        if (\is_null($criteria->getFilterSupportedEntityType())) {
            static::assertNull($passedCriteria->getFilterSupportedEntityType());
        } else {
            static::assertTrue($criteria->getFilterSupportedEntityType()->equals($passedCriteria->getFilterSupportedEntityType()));
        }
    }

    public function testDeleteFakePortalAlsoOnErrorBeforeIteration(): void
    {
        $portalNodeEntityListUiAction = $this->createMock(PortalNodeEntityListUiActionInterface::class);
        $portalNodeEntityListUiAction->method('list')
            ->willThrowException(new \LogicException('My exception', 123));

        $action = new PortalEntityListUi($this->createAuditTrailFactory(), $portalNodeEntityListUiAction);

        self::expectException(ReadException::class);
        self::expectExceptionCode(1663051795);

        \iterable_to_array($action->list(new PortalEntityListCriteria(FooBarPortal::class()), $this->createUiActionContext()));
    }

    public function testDeleteFakePortalAlsoOnErrorDuringIteration(): void
    {
        $portalNodeEntityListUiAction = $this->createMock(PortalNodeEntityListUiActionInterface::class);
        $portalNodeEntityListUiAction->method('list')
            ->willReturnCallback(static function (): iterable {
                yield from [];

                throw new \LogicException('My exception', 123);
            });

        $action = new PortalEntityListUi($this->createAuditTrailFactory(), $portalNodeEntityListUiAction);

        self::expectException(ReadException::class);
        self::expectExceptionCode(1663051795);

        \iterable_to_array($action->list(new PortalEntityListCriteria(FooBarPortal::class()), $this->createUiActionContext()));
    }

    public function provideGoodFilters(): iterable
    {
        $criteria = new PortalEntityListCriteria(FooBarPortal::class());

        yield [$criteria];

        $criteria = new PortalEntityListCriteria(FooBarPortal::class());
        $criteria->setShowEmitter(false);
        $criteria->setShowExplorer(false);
        $criteria->setShowReceiver(true);

        yield [$criteria];

        $criteria = new PortalEntityListCriteria(FooBarPortal::class());
        $criteria->setShowEmitter(false);
        $criteria->setShowExplorer(true);
        $criteria->setShowReceiver(false);

        yield [$criteria];

        $criteria = new PortalEntityListCriteria(FooBarPortal::class());
        $criteria->setShowEmitter(true);
        $criteria->setShowExplorer(false);
        $criteria->setShowReceiver(false);

        yield [$criteria];

        $criteria = new PortalEntityListCriteria(FooBarPortal::class());
        $criteria->setShowEmitter(true);
        $criteria->setShowExplorer(true);
        $criteria->setShowReceiver(false);

        yield [$criteria];

        $criteria = new PortalEntityListCriteria(FooBarPortal::class());
        $criteria->setFilterSupportedEntityType(FooBarEntity::class());

        yield [$criteria];

        $criteria = new PortalEntityListCriteria(FooBarPortal::class());
        $criteria->setFilterSupportedEntityType(new UnsafeClassString(DatasetEntityContract::class));

        yield [$criteria];
    }
}
