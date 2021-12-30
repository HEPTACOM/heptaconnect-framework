<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Web\Http;

use Heptacom\HeptaConnect\Core\Portal\FlowComponentRegistry;
use Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerFactory;
use Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackBuilderFactory;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackBuilder
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackBuilderFactory
 */
class HttpHandlerStackBuilderFactoryTest extends TestCase
{
    public function testFirstSourceBeingSourceInBuilder(): void
    {
        $portalContainerFactory = $this->createMock(PortalStackServiceContainerFactory::class);
        $logger = $this->createMock(LoggerInterface::class);
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $flowComponentRegistry = $this->createMock(FlowComponentRegistry::class);
        $container = $this->createMock(ContainerInterface::class);
        $httpHandler = $this->createMock(HttpHandlerContract::class);

        $portalContainerFactory->method('create')->with($portalNodeKey)->willReturn($container);
        $container->method('get')->with(FlowComponentRegistry::class)->willReturn($flowComponentRegistry);
        $flowComponentRegistry->method('getOrderedSources')->willReturn([PortalContract::class]);
        $flowComponentRegistry->method('getWebHttpHandlers')->with(PortalContract::class)->willReturn(new HttpHandlerCollection([$httpHandler]));
        $httpHandler->method('getPath')->willReturn('foobar');

        $factory = new HttpHandlerStackBuilderFactory($portalContainerFactory, $logger);
        $stack = $factory->createHttpHandlerStackBuilder($portalNodeKey, 'foobar');
        self::assertTrue($stack->isEmpty());
        $stack->pushDecorators();
        self::assertTrue($stack->isEmpty());
        $stack->pushSource();
        self::assertFalse($stack->isEmpty());
    }

    public function testSecondSourceBeingDecoratorInBuilder(): void
    {
        $portalContainerFactory = $this->createMock(PortalStackServiceContainerFactory::class);
        $logger = $this->createMock(LoggerInterface::class);
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $flowComponentRegistry = $this->createMock(FlowComponentRegistry::class);
        $container = $this->createMock(ContainerInterface::class);
        $httpHandler1 = $this->createMock(HttpHandlerContract::class);
        $httpHandler2 = $this->createMock(HttpHandlerContract::class);

        $portalContainerFactory->method('create')->with($portalNodeKey)->willReturn($container);
        $container->method('get')->with(FlowComponentRegistry::class)->willReturn($flowComponentRegistry);
        $flowComponentRegistry->method('getOrderedSources')->willReturn([PortalContract::class]);
        $flowComponentRegistry->method('getWebHttpHandlers')->with(PortalContract::class)->willReturn(new HttpHandlerCollection([$httpHandler1, $httpHandler2]));
        $httpHandler1->method('getPath')->willReturn('foobar');
        $httpHandler2->method('getPath')->willReturn('foobar');

        $factory = new HttpHandlerStackBuilderFactory($portalContainerFactory, $logger);
        $stack = $factory->createHttpHandlerStackBuilder($portalNodeKey, 'foobar');
        self::assertTrue($stack->isEmpty());
        $stack->pushDecorators();
        self::assertFalse($stack->isEmpty());
        $stack->pushSource();
        self::assertFalse($stack->isEmpty());
    }
}
