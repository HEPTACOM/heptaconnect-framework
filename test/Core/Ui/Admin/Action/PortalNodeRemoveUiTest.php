<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeRemoveUi;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeDeleteActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeRemove\PortalNodeRemoveCriteria;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeRemoveUi
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
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodesMissingException
 */
final class PortalNodeRemoveUiTest extends TestCase
{
    public function testSuccess(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeDeleteAction = $this->createMock(PortalNodeDeleteActionInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());

        $portalNodeGetAction->expects(static::once())->method('get')->willReturn([
            new PortalNodeGetResult($portalNodeKey, $portalNodeKey->getPortalType()),
        ]);
        $portalNodeDeleteAction->expects(static::once())->method('delete');

        $action = new PortalNodeRemoveUi($portalNodeGetAction, $portalNodeDeleteAction);
        $criteria = new PortalNodeRemoveCriteria(new PortalNodeKeyCollection([
            $portalNodeKey,
        ]));

        $action->remove($criteria);
    }

    public function testPortalNodeAlreadyDeleted(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeDeleteAction = $this->createMock(PortalNodeDeleteActionInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());

        $portalNodeGetAction->expects(static::once())->method('get')->willReturn([]);
        $portalNodeDeleteAction->expects(static::never())->method('delete');

        $action = new PortalNodeRemoveUi($portalNodeGetAction, $portalNodeDeleteAction);
        $criteria = new PortalNodeRemoveCriteria(new PortalNodeKeyCollection([
            $portalNodeKey,
        ]));

        self::expectException(PortalNodesMissingException::class);

        $action->remove($criteria);
    }

    public function testPortalNodeFailedDeleting(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeDeleteAction = $this->createMock(PortalNodeDeleteActionInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());

        $portalNodeGetAction->expects(static::once())->method('get')->willReturn([
            new PortalNodeGetResult($portalNodeKey, $portalNodeKey->getPortalType()),
        ]);
        $portalNodeDeleteAction->expects(static::once())->method('delete')->willThrowException(new \LogicException('Woops'));

        $action = new PortalNodeRemoveUi($portalNodeGetAction, $portalNodeDeleteAction);
        $criteria = new PortalNodeRemoveCriteria(new PortalNodeKeyCollection([
            $portalNodeKey,
        ]));

        self::expectException(PersistException::class);

        $action->remove($criteria);
    }

    public function testPortalNodeFailedReading(): void
    {
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalNodeDeleteAction = $this->createMock(PortalNodeDeleteActionInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class());

        $portalNodeGetAction->expects(static::once())->method('get')->willThrowException(new \LogicException('Woops'));
        $portalNodeDeleteAction->expects(static::never())->method('delete');

        $action = new PortalNodeRemoveUi($portalNodeGetAction, $portalNodeDeleteAction);
        $criteria = new PortalNodeRemoveCriteria(new PortalNodeKeyCollection([
            $portalNodeKey,
        ]));

        self::expectException(PersistException::class);

        $action->remove($criteria);
    }
}
