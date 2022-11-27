<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Web\Http;

use Heptacom\HeptaConnect\Core\Web\Http\Handler\HttpMiddlewareChainHandler;
use Heptacom\HeptaConnect\Core\Web\Http\HttpHandleFlowHttpHandlersFactory;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\Handler\HttpMiddlewareChainHandler
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\HttpHandleFlowHttpHandlersFactory
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection
 */
final class HttpHandleFlowHttpHandlersFactoryTest extends TestCase
{
    public function testCollectionContainsExpectedServices(): void
    {
        $factory = new HttpHandleFlowHttpHandlersFactory();
        $result = $factory->createHttpHandlers($this->createMock(PortalNodeKeyInterface::class), '');

        static::assertCount(1, $result);
        static::assertInstanceOf(HttpMiddlewareChainHandler::class, $result[0]);
    }
}
