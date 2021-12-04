<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Builder;

use Heptacom\HeptaConnect\Portal\Base\Builder\Exception\InvalidResultException;
use Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\ConfigurationContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandleContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStack;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Builder\HttpHandlerBuilder
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Exception\InvalidResultException
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Token\HttpHandlerToken
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStack
 */
class FlowComponentTest extends TestCase
{
    public function testBuildHttpHandlers(): void
    {
        $flowComponent = new FlowComponent();
        $config = $this->createMock(ConfigurationContract::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $context = $this->createMock(HttpHandleContextInterface::class);
        $stack = new HttpHandlerStack([]);

        $context->method('getContainer')->willReturn($container);
        $container->method('get')->willReturnCallback(static fn (string $id) => [
            ConfigurationContract::class => $config,
        ][$id] ?? null);

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->run(static fn () => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('get');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->options(static fn () => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('options');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->get(static fn () => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('get');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->post(static fn () => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('post');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->put(static fn () => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('put');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->patch(static fn () => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('patch');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->delete(static fn () => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('delete');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));
    }

    public function testLogImplementationConflictsWhenBuildingHttpHandlers(): void
    {
        $flowComponent = new FlowComponent();
        $config = $this->createMock(ConfigurationContract::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $context = $this->createMock(HttpHandleContextInterface::class);
        $stack = new HttpHandlerStack([]);
        $logger = $this->createMock(LoggerInterface::class);

        $context->method('getContainer')->willReturn($container);
        $container->method('get')->willReturnCallback(static fn (string $id) => [
            ConfigurationContract::class => $config,
        ][$id] ?? null);
        $logger->expects(self::exactly(6))->method('warning');
        $flowComponent->setLogger($logger);

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->run(static fn () => $response)->options(static fn () => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('options');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->run(static fn () => $response)->get(static fn () => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('get');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->run(static fn () => $response)->post(static fn () => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('post');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->run(static fn () => $response)->put(static fn () => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('put');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->run(static fn () => $response)->patch(static fn () => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('patch');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->run(static fn () => $response)->delete(static fn () => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('delete');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));
    }

    public function testFailClosuresThatDoNotReturnResponses(): void
    {
        $flowComponent = new FlowComponent();
        $config = $this->createMock(ConfigurationContract::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $context = $this->createMock(HttpHandleContextInterface::class);
        $stack = new HttpHandlerStack([]);

        $context->method('getContainer')->willReturn($container);
        $container->method('get')->willReturnCallback(static fn (string $id) => [
                ConfigurationContract::class => $config,
            ][$id] ?? null);

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->run(static fn () => '');
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('get');
        static::assertCount(1, $handlers);

        try {
            $handlers[0]->handle($request, $response, $context, $stack);
        } catch (InvalidResultException $throwable) {
            self::assertSame('Short-noted HttpHandler failed in run to return Psr\Http\Message\ResponseInterface', $throwable->getMessage());
        }

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->options(static fn () => '');
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('options');
        static::assertCount(1, $handlers);

        try {
            $handlers[0]->handle($request, $response, $context, $stack);
        } catch (InvalidResultException $throwable) {
            self::assertSame('Short-noted HttpHandler failed in options to return Psr\Http\Message\ResponseInterface', $throwable->getMessage());
        }

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->get(static fn () => '');
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('get');
        static::assertCount(1, $handlers);

        try {
            $handlers[0]->handle($request, $response, $context, $stack);
        } catch (InvalidResultException $throwable) {
            self::assertSame('Short-noted HttpHandler failed in get to return Psr\Http\Message\ResponseInterface', $throwable->getMessage());
        }

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->post(static fn () => '');
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('post');
        static::assertCount(1, $handlers);

        try {
            $handlers[0]->handle($request, $response, $context, $stack);
        } catch (InvalidResultException $throwable) {
            self::assertSame('Short-noted HttpHandler failed in post to return Psr\Http\Message\ResponseInterface', $throwable->getMessage());
        }

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->put(static fn () => '');
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('put');
        static::assertCount(1, $handlers);

        try {
            $handlers[0]->handle($request, $response, $context, $stack);
        } catch (InvalidResultException $throwable) {
            self::assertSame('Short-noted HttpHandler failed in put to return Psr\Http\Message\ResponseInterface', $throwable->getMessage());
        }

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->patch(static fn () => '');
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('patch');
        static::assertCount(1, $handlers);

        try {
            $handlers[0]->handle($request, $response, $context, $stack);
        } catch (InvalidResultException $throwable) {
            self::assertSame('Short-noted HttpHandler failed in patch to return Psr\Http\Message\ResponseInterface', $throwable->getMessage());
        }

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->delete(static fn () => '');
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('delete');
        static::assertCount(1, $handlers);

        try {
            $handlers[0]->handle($request, $response, $context, $stack);
        } catch (InvalidResultException $throwable) {
            self::assertSame('Short-noted HttpHandler failed in delete to return Psr\Http\Message\ResponseInterface', $throwable->getMessage());
        }
    }

    public function testResolveContainerArguments(): void
    {
        $flowComponent = new FlowComponent();
        $config = $this->createMock(ConfigurationContract::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $context = $this->createMock(HttpHandleContextInterface::class);
        $stack = new HttpHandlerStack([]);

        $context->method('getContainer')->willReturn($container);
        $container->method('get')->willReturnCallback(static fn (string $id) => [
                ConfigurationContract::class => $config,
            ][$id] ?? null);

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->run(static fn (ConfigurationContract $config) => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('get');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));
    }

    public function testResolveRequestArgument(): void
    {
        $flowComponent = new FlowComponent();
        $config = $this->createMock(ConfigurationContract::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $context = $this->createMock(HttpHandleContextInterface::class);
        $stack = new HttpHandlerStack([]);

        $context->method('getContainer')->willReturn($container);
        $container->method('get')->willReturnCallback(static fn (string $id) => [
                ConfigurationContract::class => $config,
            ][$id] ?? null);

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->run(static fn (ServerRequestInterface $request) => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('get');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->run(static fn (RequestInterface $request) => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('get');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));
    }

    public function testResolveResponseArgument(): void
    {
        $flowComponent = new FlowComponent();
        $config = $this->createMock(ConfigurationContract::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $context = $this->createMock(HttpHandleContextInterface::class);
        $stack = new HttpHandlerStack([]);

        $context->method('getContainer')->willReturn($container);
        $container->method('get')->willReturnCallback(static fn (string $id) => [
                ConfigurationContract::class => $config,
            ][$id] ?? null);

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->run(static fn (ResponseInterface $r) => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('get');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));
    }

    public function testResolveContextArgument(): void
    {
        $flowComponent = new FlowComponent();
        $config = $this->createMock(ConfigurationContract::class);
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $context = $this->createMock(HttpHandleContextInterface::class);
        $stack = new HttpHandlerStack([]);

        $context->method('getContainer')->willReturn($container);
        $container->method('get')->willReturnCallback(static fn (string $id) => [
                ConfigurationContract::class => $config,
            ][$id] ?? null);

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->run(static fn (HttpHandleContextInterface $c) => $response);
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('get');
        static::assertCount(1, $handlers);
        static::assertSame($response, $handlers[0]->handle($request, $response, $context, $stack));
    }
}
