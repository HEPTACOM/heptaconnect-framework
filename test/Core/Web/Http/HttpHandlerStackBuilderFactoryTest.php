<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Web\Http;

use Heptacom\HeptaConnect\Core\Portal\Contract\PortalNodeContainerFacadeContract;
use Heptacom\HeptaConnect\Core\Portal\FlowComponentRegistry;
use Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerFactory;
use Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackBuilderFactory;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStackIdentifier;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackBuilder
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackBuilderFactory
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStackIdentifier
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 */
final class HttpHandlerStackBuilderFactoryTest extends TestCase
{
    public function testFirstSourceBeingSourceInBuilder(): void
    {
        $portalContainerFactory = $this->createMock(PortalStackServiceContainerFactory::class);
        $logger = $this->createMock(LoggerInterface::class);
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $flowComponentRegistry = $this->createMock(FlowComponentRegistry::class);
        $containerFacade = $this->createMock(PortalNodeContainerFacadeContract::class);
        $httpHandler = $this->createMock(HttpHandlerContract::class);

        $portalContainerFactory->method('create')->with($portalNodeKey)->willReturn($containerFacade);
        $containerFacade->method('getFlowComponentRegistry')->willReturn($flowComponentRegistry);
        $flowComponentRegistry->method('getWebHttpHandlers')->willReturn(new HttpHandlerCollection([$httpHandler]));
        $httpHandler->method('supports')->willReturn('foobar');

        $factory = new HttpHandlerStackBuilderFactory($portalContainerFactory, $logger);
        $stack = $factory->createHttpHandlerStackBuilder(new HttpHandlerStackIdentifier($portalNodeKey, 'foobar'));
        static::assertTrue($stack->isEmpty());
        $stack->pushDecorators();
        static::assertTrue($stack->isEmpty());
        $stack->pushSource();
        static::assertFalse($stack->isEmpty());
    }

    public function testSecondSourceBeingDecoratorInBuilder(): void
    {
        $portalContainerFactory = $this->createMock(PortalStackServiceContainerFactory::class);
        $logger = $this->createMock(LoggerInterface::class);
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $flowComponentRegistry = $this->createMock(FlowComponentRegistry::class);
        $containerFacade = $this->createMock(PortalNodeContainerFacadeContract::class);
        $httpHandler1 = $this->createMock(HttpHandlerContract::class);
        $httpHandler2 = $this->createMock(HttpHandlerContract::class);

        $portalContainerFactory->method('create')->with($portalNodeKey)->willReturn($containerFacade);
        $containerFacade->method('getFlowComponentRegistry')->willReturn($flowComponentRegistry);
        $flowComponentRegistry->method('getWebHttpHandlers')->willReturn(new HttpHandlerCollection([$httpHandler1, $httpHandler2]));
        $httpHandler1->method('supports')->willReturn('foobar');
        $httpHandler2->method('supports')->willReturn('foobar');

        $factory = new HttpHandlerStackBuilderFactory($portalContainerFactory, $logger);
        $stack = $factory->createHttpHandlerStackBuilder(new HttpHandlerStackIdentifier($portalNodeKey, 'foobar'));
        static::assertTrue($stack->isEmpty());
        $stack->pushDecorators();
        static::assertFalse($stack->isEmpty());
        $stack->pushSource();
        static::assertFalse($stack->isEmpty());
    }
}
