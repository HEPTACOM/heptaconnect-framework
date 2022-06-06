<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Ui\Admin\Action;

use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarPortal;
use Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeAddUi;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResults;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Find\PortalNodeAliasFindResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeCreateActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeAlias\PortalNodeAliasFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeAdd\PortalNodeAddPayload;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeAliasIsAlreadyAssignedException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Ui\Admin\Action\PortalNodeAddUi
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResults
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Find\PortalNodeAliasFindCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Find\PortalNodeAliasFindResult
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeAdd\PortalNodeAddPayload
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeAdd\PortalNodeAddResult
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PersistException
 * @covers \Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\PortalNodeAliasIsAlreadyAssignedException
 */
final class PortalNodeAddUiTest extends TestCase
{
    public function testPayloadIsWritten(): void
    {
        $portalNodeCreateAction = $this->createMock(PortalNodeCreateActionInterface::class);
        $portalNodeAliasFindAction = $this->createMock(PortalNodeAliasFindActionInterface::class);
        $portalNodeKey = new PreviewPortalNodeKey(FooBarPortal::class);

        $portalNodeCreateAction->method('create')->willReturn(new PortalNodeCreateResults([
            new PortalNodeCreateResult($portalNodeKey),
        ]));
        $portalNodeAliasFindAction->method('find')->willReturn([]);

        $action = new PortalNodeAddUi($portalNodeCreateAction, $portalNodeAliasFindAction);
        $payload = new PortalNodeAddPayload(FooBarPortal::class);

        $result = $action->add($payload);

        static::assertSame($portalNodeKey, $result->getPortalNodeKey());
    }

    public function testPayloadPortalIsNotCreatedDoesNotExist(): void
    {
        $portalNodeCreateAction = $this->createMock(PortalNodeCreateActionInterface::class);
        $portalNodeAliasFindAction = $this->createMock(PortalNodeAliasFindActionInterface::class);

        $portalNodeCreateAction->method('create')->willReturn(new PortalNodeCreateResults());
        $portalNodeAliasFindAction->method('find')->willReturn([]);

        $action = new PortalNodeAddUi($portalNodeCreateAction, $portalNodeAliasFindAction);
        $payload = new PortalNodeAddPayload(FooBarPortal::class);

        self::expectException(PersistException::class);

        $action->add($payload);
    }

    public function testPayloadAliasIsAlreadyTaken(): void
    {
        $portalNodeCreateAction = $this->createMock(PortalNodeCreateActionInterface::class);
        $portalNodeAliasFindAction = $this->createMock(PortalNodeAliasFindActionInterface::class);

        $portalNodeCreateAction->method('create')->willReturn(new PortalNodeCreateResults());
        $portalNodeAliasFindAction->method('find')->willReturn([
            new PortalNodeAliasFindResult(new PreviewPortalNodeKey(FooBarPortal::class), 'foobar'),
        ]);

        $action = new PortalNodeAddUi($portalNodeCreateAction, $portalNodeAliasFindAction);
        $payload = new PortalNodeAddPayload(FooBarPortal::class);
        $payload->setPortalNodeAlias('foobar');

        self::expectException(PortalNodeAliasIsAlreadyAssignedException::class);

        $action->add($payload);
    }
}
