<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Test\Fixture\UninstantiablePortal;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeRemoveUi;
use Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparationResult;
use Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparator;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeRemove\PortalNodeRemoveCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContext
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\Context\UiActionContextFactory
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeRemoveUi
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Audit\AuditTrail
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Support\PortalNodeExistenceSeparationResult
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeRemove\PortalNodeRemoveCriteria
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\UiActionType
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException
 */
final class PortalNodeRemoveUiTest extends TestCase
{
    use UiActionTestTrait;

    public function testSuccess(): void
    {
        $portalNodeExistenceSeparator = $this->createMock(PortalNodeExistenceSeparator::class);
        $portalNodeDeleteAction = $this->createMock(PortalNodeDeleteActionInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $portalNodeKey2 = new PreviewPortalNodeKey(UninstantiablePortal::class());

        $portalNodeExistenceSeparator->method('separateKeys')
            ->willReturn(new PortalNodeExistenceSeparationResult(
                new PortalNodeKeyCollection(),
                new PortalNodeKeyCollection([
                    $portalNodeKey,
                    $portalNodeKey2,
                ]),
                new PortalNodeKeyCollection(),
            ));
        $portalNodeDeleteAction->expects(static::once())->method('delete');

        $action = new PortalNodeRemoveUi(
            $this->createAuditTrailFactory(),
            $portalNodeExistenceSeparator,
            $portalNodeDeleteAction
        );
        $criteria = new PortalNodeRemoveCriteria(new PortalNodeKeyCollection([
            $portalNodeKey,
        ]));

        $action->remove($criteria, $this->createUiActionContext());
    }

    public function testPortalNodeAlreadyDeleted(): void
    {
        $portalNodeExistenceSeparator = $this->createMock(PortalNodeExistenceSeparator::class);
        $portalNodeDeleteAction = $this->createMock(PortalNodeDeleteActionInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $portalNodeKey2 = new PreviewPortalNodeKey(UninstantiablePortal::class());

        $portalNodeExistenceSeparator->method('separateKeys')
            ->willReturn(new PortalNodeExistenceSeparationResult(
                new PortalNodeKeyCollection(),
                new PortalNodeKeyCollection([$portalNodeKey2]),
                new PortalNodeKeyCollection([$portalNodeKey]),
            ));
        $portalNodeDeleteAction->expects(static::never())->method('delete');

        $action = new PortalNodeRemoveUi($this->createAuditTrailFactory(), $portalNodeExistenceSeparator, $portalNodeDeleteAction);
        $criteria = new PortalNodeRemoveCriteria(new PortalNodeKeyCollection([$portalNodeKey,
            $portalNodeKey,
        ]));

        self::expectException(PortalNodesMissingException::class);

        $action->remove($criteria, $this->createUiActionContext());
    }

    public function testPortalNodeFailedDeleting(): void
    {
        $portalNodeExistenceSeparator = $this->createMock(PortalNodeExistenceSeparator::class);
        $portalNodeDeleteAction = $this->createMock(PortalNodeDeleteActionInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $portalNodeKey2 = new PreviewPortalNodeKey(UninstantiablePortal::class());

        $portalNodeExistenceSeparator->method('separateKeys')
            ->willReturn(new PortalNodeExistenceSeparationResult(
                new PortalNodeKeyCollection(),
                new PortalNodeKeyCollection([
                    $portalNodeKey,
                    $portalNodeKey2,
                ]),
                new PortalNodeKeyCollection(),
            ));
        $portalNodeDeleteAction->expects(static::once())->method('delete')->willThrowException(new \LogicException('Woops'));

        $action = new PortalNodeRemoveUi($this->createAuditTrailFactory(), $portalNodeExistenceSeparator, $portalNodeDeleteAction);
        $criteria = new PortalNodeRemoveCriteria(new PortalNodeKeyCollection([
            $portalNodeKey,
        ]));

        self::expectException(PersistException::class);

        $action->remove($criteria, $this->createUiActionContext());
    }

    public function testPortalNodeFailedReading(): void
    {
        $portalNodeExistenceSeparator = $this->createMock(PortalNodeExistenceSeparator::class);
        $portalNodeDeleteAction = $this->createMock(PortalNodeDeleteActionInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());
        $portalNodeKey2 = new PreviewPortalNodeKey(UninstantiablePortal::class());

        $portalNodeExistenceSeparator->expects(static::once())->method('separateKeys')->willThrowException(new \LogicException('Woops'));
        $portalNodeDeleteAction->expects(static::never())->method('delete');

        $action = new PortalNodeRemoveUi($this->createAuditTrailFactory(), $portalNodeExistenceSeparator, $portalNodeDeleteAction);
        $criteria = new PortalNodeRemoveCriteria(new PortalNodeKeyCollection([
            $portalNodeKey,
            $portalNodeKey2,
        ]));

        self::expectException(ReadException::class);

        $action->remove($criteria, $this->createUiActionContext());
    }
}
