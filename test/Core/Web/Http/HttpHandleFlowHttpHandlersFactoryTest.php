<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Web\Http;

use Heptacom\HeptaConnect\Core\Web\Http\Handler\HttpMiddlewareChainHandler;
use Heptacom\HeptaConnect\Core\Web\Http\HttpHandleFlowHttpHandlersFactory;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStackIdentifier;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(HttpMiddlewareChainHandler::class)]
#[CoversClass(HttpHandleFlowHttpHandlersFactory::class)]
#[CoversClass(HttpHandlerCollection::class)]
#[CoversClass(HttpHandlerStackIdentifier::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
final class HttpHandleFlowHttpHandlersFactoryTest extends TestCase
{
    public function testCollectionContainsExpectedServices(): void
    {
        $factory = new HttpHandleFlowHttpHandlersFactory();
        $result = $factory->createHttpHandlers(
            new HttpHandlerStackIdentifier($this->createMock(PortalNodeKeyInterface::class), '')
        );

        static::assertCount(1, $result);
        static::assertInstanceOf(HttpMiddlewareChainHandler::class, $result[0]);
    }
}
