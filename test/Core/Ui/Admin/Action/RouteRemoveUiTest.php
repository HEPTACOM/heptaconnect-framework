<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteRemoveUi;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Storage\Base\RouteKeyCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteRemove\RouteRemoveCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\RoutesMissingException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteRemoveUi
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Delete\RouteDeleteCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Storage\Base\RouteKeyCollection
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteRemove\RouteRemoveCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\RoutesMissingException
 */
final class RouteRemoveUiTest extends TestCase
{
    use UiActionTestTrait;

    public function testSuccess(): void
    {
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $routeDeleteAction = $this->createMock(RouteDeleteActionInterface::class);
        $routeKey = $this->createMock(RouteKeyInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());

        $routeKey->method('equals')
            ->willReturnCallback(static fn (StorageKeyInterface $key): bool => $key === $routeKey);
        $routeGetAction->expects(static::once())->method('get')->willReturn([
            new RouteGetResult($routeKey, $portalNodeKey, $portalNodeKey, FooBarEntity::class(), []),
        ]);

        $routeDeleteAction->expects(static::once())->method('delete');

        $action = new RouteRemoveUi($this->createAuditTrailFactory(), $routeGetAction, $routeDeleteAction);
        $criteria = new RouteRemoveCriteria(new RouteKeyCollection([
            $routeKey,
        ]));

        $action->remove($criteria, $this->createUiActionContext());
    }

    public function testRouteAlreadyDeleted(): void
    {
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $routeDeleteAction = $this->createMock(RouteDeleteActionInterface::class);
        $routeKey = $this->createMock(RouteKeyInterface::class);

        $routeKey->method('equals')
            ->willReturnCallback(static fn (StorageKeyInterface $key): bool => $key === $routeKey);
        $routeGetAction->expects(static::once())->method('get')->willReturn([]);
        $routeDeleteAction->expects(static::never())->method('delete');

        $action = new RouteRemoveUi($this->createAuditTrailFactory(), $routeGetAction, $routeDeleteAction);
        $criteria = new RouteRemoveCriteria(new RouteKeyCollection([
            $routeKey,
        ]));

        self::expectException(RoutesMissingException::class);

        $action->remove($criteria, $this->createUiActionContext());
    }

    public function testRouteFailedDeleting(): void
    {
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $routeDeleteAction = $this->createMock(RouteDeleteActionInterface::class);
        $routeKey = $this->createMock(RouteKeyInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());

        $routeKey->method('equals')
            ->willReturnCallback(static fn (StorageKeyInterface $key): bool => $key === $routeKey);

        $routeGetAction->expects(static::once())->method('get')->willReturn([
            new RouteGetResult($routeKey, $portalNodeKey, $portalNodeKey, FooBarEntity::class(), []),
        ]);
        $routeDeleteAction->expects(static::once())->method('delete')->willThrowException(new \LogicException('Woops'));

        $action = new RouteRemoveUi($this->createAuditTrailFactory(), $routeGetAction, $routeDeleteAction);
        $criteria = new RouteRemoveCriteria(new RouteKeyCollection([
            $routeKey,
        ]));

        self::expectException(PersistException::class);

        $action->remove($criteria, $this->createUiActionContext());
    }

    public function testRouteFailedReading(): void
    {
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $routeDeleteAction = $this->createMock(RouteDeleteActionInterface::class);
        $routeKey = $this->createMock(RouteKeyInterface::class);

        $routeKey->method('equals')
            ->willReturnCallback(static fn (StorageKeyInterface $key): bool => $key === $routeKey);
        $routeGetAction->expects(static::once())->method('get')->willThrowException(new \LogicException('Woops'));
        $routeDeleteAction->expects(static::never())->method('delete');

        $action = new RouteRemoveUi($this->createAuditTrailFactory(), $routeGetAction, $routeDeleteAction);
        $criteria = new RouteRemoveCriteria(new RouteKeyCollection([
            $routeKey,
        ]));

        self::expectException(PersistException::class);

        $action->remove($criteria, $this->createUiActionContext());
    }
}
