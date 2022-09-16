<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Test\Fixture\DependentPortal;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteAddUi;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreateResults;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Find\RouteFindResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\RouteGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayloadCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\RouteAddFailedException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\RouteAlreadyExistsException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\RouteAddUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreatePayloads
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreateResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Create\RouteCreateResults
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Delete\RouteDeleteCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Find\RouteFindCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Find\RouteFindResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Storage\Base\RouteKeyCollection
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayload
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddPayloadCollection
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd\RouteAddResultCollection
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeAliasIsAlreadyAssignedException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\RouteAddFailedException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\RouteAlreadyExistsException
 */
final class RouteAddUiTest extends TestCase
{
    use UiActionTestTrait;

    public function testPayloadIsWritten(): void
    {
        $routeCreateAction = $this->createMock(RouteCreateActionInterface::class);
        $routeFindAction = $this->createMock(RouteFindActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $routeDeleteAction = $this->createMock(RouteDeleteActionInterface::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeKey = $this->createMock(RouteKeyInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());

        $routeCreateAction->method('create')->willReturn(new RouteCreateResults([
            new RouteCreateResult($routeKey),
        ]));
        $routeFindAction->method('find')->willReturn(null);
        $routeGetAction->method('get')->willReturn([
            new RouteGetResult($routeKey, $portalNodeKey, $portalNodeKey, FooBarEntity::class(), []),
        ]);
        $portalNodeGetAction->method('get')
            ->willReturn([
                new PortalNodeGetResult($portalNodeKey, $portalNodeKey->getPortalType()),
            ]);
        $routeDeleteAction->expects(static::never())->method('delete');

        $action = new RouteAddUi(
            $this->createAuditTrailFactory(),
            $routeCreateAction,
            $routeFindAction,
            $routeGetAction,
            $routeDeleteAction,
            $portalNodeGetAction
        );

        $result = $action->add(
            new RouteAddPayloadCollection([
                new RouteAddPayload($portalNodeKey, $portalNodeKey, FooBarEntity::class()),
            ]),
            $this->createUiActionContext()
        );

        static::assertSame($routeKey, $result->first()->getRouteKey());
    }

    public function testPayloadIsWrittenButNotReturnedFromStorageSoItMustBeReverted(): void
    {
        $routeCreateAction = $this->createMock(RouteCreateActionInterface::class);
        $routeFindAction = $this->createMock(RouteFindActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $routeDeleteAction = $this->createMock(RouteDeleteActionInterface::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $routeKey = $this->createMock(RouteKeyInterface::class);
        $portalNodeKeyA = new PreviewPortalNodeKey(FooBarPortal::class());
        $portalNodeKeyB = new PreviewPortalNodeKey(DependentPortal::class());

        $routeCreateAction->method('create')->willReturn(new RouteCreateResults([
            new RouteCreateResult($routeKey),
        ]));
        $routeFindAction->method('find')->willReturn(null);
        $routeGetAction->method('get')->willReturn([
            new RouteGetResult($routeKey, $portalNodeKeyA, $portalNodeKeyB, FooBarEntity::class(), []),
        ]);
        $portalNodeGetAction->method('get')
            ->willReturn([
                new PortalNodeGetResult($portalNodeKeyA, $portalNodeKeyA->getPortalType()),
                new PortalNodeGetResult($portalNodeKeyB, $portalNodeKeyB->getPortalType()),
            ]);
        $routeDeleteAction->expects(static::once())->method('delete');
        $routeKey->method('equals')
            ->willReturnCallback(static fn (RouteKeyInterface $key): bool => $key === $routeKey);

        $action = new RouteAddUi(
            $this->createAuditTrailFactory(),
            $routeCreateAction,
            $routeFindAction,
            $routeGetAction,
            $routeDeleteAction,
            $portalNodeGetAction
        );

        self::expectException(RouteAddFailedException::class);
        self::expectExceptionCode(1654573097);

        $action->add(
            new RouteAddPayloadCollection([
                new RouteAddPayload($portalNodeKeyA, $portalNodeKeyB, FooBarEntity::class()),
                new RouteAddPayload($portalNodeKeyB, $portalNodeKeyA, FooBarEntity::class()),
            ]),
            $this->createUiActionContext()
        );
    }

    public function testPortalNodeMissingCheck(): void
    {
        $routeCreateAction = $this->createMock(RouteCreateActionInterface::class);
        $routeFindAction = $this->createMock(RouteFindActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $routeDeleteAction = $this->createMock(RouteDeleteActionInterface::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());

        $routeCreateAction->method('create')->willReturn(new RouteCreateResults());
        $routeFindAction->method('find')->willReturn(null);
        $portalNodeGetAction->method('get')->willReturn([]);
        $routeDeleteAction->expects(static::never())->method('delete');

        $action = new RouteAddUi(
            $this->createAuditTrailFactory(),
            $routeCreateAction,
            $routeFindAction,
            $routeGetAction,
            $routeDeleteAction,
            $portalNodeGetAction
        );

        self::expectException(PortalNodesMissingException::class);

        $action->add(
            new RouteAddPayloadCollection([
                new RouteAddPayload($portalNodeKey, $portalNodeKey, FooBarEntity::class()),
            ]),
            $this->createUiActionContext()
        );
    }

    public function testRouteAlreadyExists(): void
    {
        $routeCreateAction = $this->createMock(RouteCreateActionInterface::class);
        $routeFindAction = $this->createMock(RouteFindActionInterface::class);
        $routeGetAction = $this->createMock(RouteGetActionInterface::class);
        $routeDeleteAction = $this->createMock(RouteDeleteActionInterface::class);
        $routeKey = $this->createMock(RouteKeyInterface::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());

        $routeCreateAction->method('create')->willReturn(new RouteCreateResults());
        $routeFindAction->method('find')->willReturn(new RouteFindResult($routeKey));
        $portalNodeGetAction->method('get')
            ->willReturn([
                new PortalNodeGetResult($portalNodeKey, $portalNodeKey->getPortalType()),
            ]);
        $routeDeleteAction->expects(static::never())->method('delete');

        $action = new RouteAddUi(
            $this->createAuditTrailFactory(),
            $routeCreateAction,
            $routeFindAction,
            $routeGetAction,
            $routeDeleteAction,
            $portalNodeGetAction
        );

        self::expectException(RouteAlreadyExistsException::class);

        $action->add(
            new RouteAddPayloadCollection([
                new RouteAddPayload($portalNodeKey, $portalNodeKey, FooBarEntity::class()),
            ]),
            $this->createUiActionContext()
        );
    }
}
