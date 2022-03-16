<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Builder;

use Heptacom\HeptaConnect\Core\Portal\PortalConfiguration;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Builder\Component\Emitter;
use Heptacom\HeptaConnect\Portal\Base\Builder\Component\Explorer;
use Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler;
use Heptacom\HeptaConnect\Portal\Base\Builder\Component\Receiver;
use Heptacom\HeptaConnect\Portal\Base\Builder\Component\StatusReporter;
use Heptacom\HeptaConnect\Portal\Base\Builder\Exception\InvalidResultException;
use Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\HttpHandlerToken;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\StatusReporterToken;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterStack;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerStack;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\ConfigurationContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverStack;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReportingContextInterface;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\StatusReporterStack;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandleContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStack;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Builder\HttpHandlerBuilder
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Component\Emitter
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Component\Explorer
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Component\Receiver
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Component\StatusReporter
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Token\HttpHandlerToken
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Token\StatusReporterToken
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Component\HttpHandler
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Exception\InvalidResultException
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\BindThisTrait
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\FlowComponent
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\Token\HttpHandlerToken
 * @covers \Heptacom\HeptaConnect\Portal\Base\Builder\ResolveArgumentsTrait
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStack
 */
final class FlowComponentTest extends TestCase
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
        $logger->expects(static::exactly(6))->method('warning');
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
            static::assertSame('Short-noted HttpHandler failed in run to return Psr\Http\Message\ResponseInterface', $throwable->getMessage());
        }

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->options(static fn () => '');
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('options');
        static::assertCount(1, $handlers);

        try {
            $handlers[0]->handle($request, $response, $context, $stack);
        } catch (InvalidResultException $throwable) {
            static::assertSame('Short-noted HttpHandler failed in options to return Psr\Http\Message\ResponseInterface', $throwable->getMessage());
        }

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->get(static fn () => '');
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('get');
        static::assertCount(1, $handlers);

        try {
            $handlers[0]->handle($request, $response, $context, $stack);
        } catch (InvalidResultException $throwable) {
            static::assertSame('Short-noted HttpHandler failed in get to return Psr\Http\Message\ResponseInterface', $throwable->getMessage());
        }

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->post(static fn () => '');
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('post');
        static::assertCount(1, $handlers);

        try {
            $handlers[0]->handle($request, $response, $context, $stack);
        } catch (InvalidResultException $throwable) {
            static::assertSame('Short-noted HttpHandler failed in post to return Psr\Http\Message\ResponseInterface', $throwable->getMessage());
        }

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->put(static fn () => '');
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('put');
        static::assertCount(1, $handlers);

        try {
            $handlers[0]->handle($request, $response, $context, $stack);
        } catch (InvalidResultException $throwable) {
            static::assertSame('Short-noted HttpHandler failed in put to return Psr\Http\Message\ResponseInterface', $throwable->getMessage());
        }

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->patch(static fn () => '');
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('patch');
        static::assertCount(1, $handlers);

        try {
            $handlers[0]->handle($request, $response, $context, $stack);
        } catch (InvalidResultException $throwable) {
            static::assertSame('Short-noted HttpHandler failed in patch to return Psr\Http\Message\ResponseInterface', $throwable->getMessage());
        }

        $handlerBuilder = FlowComponent::httpHandler('foobar');
        $handlerBuilder->delete(static fn () => '');
        $handlers = \iterable_to_array($flowComponent->buildHttpHandlers());
        $request->method('getMethod')->willReturn('delete');
        static::assertCount(1, $handlers);

        try {
            $handlers[0]->handle($request, $response, $context, $stack);
        } catch (InvalidResultException $throwable) {
            static::assertSame('Short-noted HttpHandler failed in delete to return Psr\Http\Message\ResponseInterface', $throwable->getMessage());
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

    public function testAllowThisInToken(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->willReturnCallback(static fn (string $id) => [
            LoggerInterface::class => $logger,
            ConfigurationContract::class => new PortalConfiguration([]),
        ][$id] ?? null);
        $container->method('has')->willReturnCallback(static fn (string $id) => \in_array($id, [
            LoggerInterface::class,
            ConfigurationContract::class,
        ], true));

        $exploreContext = $this->createMock(ExploreContextInterface::class);
        $emitContext = $this->createMock(EmitContextInterface::class);
        $receiveContext = $this->createMock(ReceiveContextInterface::class);
        $statusReportingContext = $this->createMock(StatusReportingContextInterface::class);
        $httpHandleContext = $this->createMock(HttpHandleContextInterface::class);

        $exploreContext->method('getContainer')->willReturn($container);
        $emitContext->method('getContainer')->willReturn($container);
        $receiveContext->method('getContainer')->willReturn($container);
        $statusReportingContext->method('getContainer')->willReturn($container);
        $httpHandleContext->method('getContainer')->willReturn($container);

        $thisClasses = [];
        $supports = [];

        $explorerToken = new ExplorerToken(FirstEntity::class);
        $explorerToken->setRun(function () use (&$supports, &$thisClasses): array {
            /** @var $this ExplorerContract */
            $thisClasses[] = \get_class($this);
            $supports[] = $this->supports();
            return [];
        });
        $explorer = new Explorer($explorerToken);

        $emitterToken = new EmitterToken(FirstEntity::class);
        $emitterToken->setBatch(function () use (&$supports, &$thisClasses): array {
            /** @var $this EmitterContract */
            $thisClasses[] = \get_class($this);
            $supports[] = $this->supports();
            return [];
        });
        $emitter = new Emitter($emitterToken);

        $receiverToken = new ReceiverToken(FirstEntity::class);
        $receiverToken->setBatch(function () use (&$supports, &$thisClasses): void {
            /** @var $this ReceiverContract */
            $thisClasses[] = \get_class($this);
            $supports[] = $this->supports();
        });
        $receiver = new Receiver($receiverToken);

        $statusReporterToken = new StatusReporterToken(StatusReporterContract::TOPIC_HEALTH);
        $statusReporterToken->setRun(function () use (&$supports, &$thisClasses): array {
            /** @var $this StatusReporterContract */
            $thisClasses[] = \get_class($this);
            $supports[] = $this->supportsTopic();
            return [];
        });
        $statusReporter = new StatusReporter($statusReporterToken);

        $httpHandlerToken = new HttpHandlerToken('/');
        $httpHandlerToken->setRun(function (ResponseInterface $response) use (&$supports, &$thisClasses): ResponseInterface {
            /** @var $this HttpHandlerContract */
            $thisClasses[] = \get_class($this);
            $supports[] = $this->getPath();
            return $response;
        });
        $httpHandler = new HttpHandler($httpHandlerToken);

        \iterable_to_array($explorer->explore($exploreContext, new ExplorerStack([])));
        \iterable_to_array($emitter->emit([], $emitContext, new EmitterStack([], FirstEntity::class)));
        \iterable_to_array($receiver->receive(
            new TypedDatasetEntityCollection(FirstEntity::class, [new FirstEntity()]),
            $receiveContext,
            new ReceiverStack([])
        ));
        $statusReporter->report($statusReportingContext, new StatusReporterStack([], $logger));
        $httpHandler->handle(
            $this->createMock(ServerRequestInterface::class),
            $this->createMock(ResponseInterface::class),
            $httpHandleContext,
            new HttpHandlerStack([])
        );

        static::assertSame([
            Explorer::class,
            Emitter::class,
            Receiver::class,
            StatusReporter::class,
            HttpHandler::class,
        ], $thisClasses);

        static::assertSame([
            FirstEntity::class,
            FirstEntity::class,
            FirstEntity::class,
            StatusReporter::TOPIC_HEALTH,
            '',
        ], $supports);
    }
}
