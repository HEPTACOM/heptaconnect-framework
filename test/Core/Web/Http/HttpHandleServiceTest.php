<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Web\Http;

use Heptacom\HeptaConnect\Core\Component\LogMessage;
use Heptacom\HeptaConnect\Core\Portal\AbstractPortalNodeContext;
use Heptacom\HeptaConnect\Core\Portal\Contract\PortalNodeContainerFacadeContract;
use Heptacom\HeptaConnect\Core\Portal\PortalNodeContainerFacade;
use Heptacom\HeptaConnect\Core\Support\HttpMiddlewareCollector;
use Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandleContextFactoryInterface;
use Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandleFlowHttpHandlersFactoryInterface;
use Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandlerStackBuilderFactoryInterface;
use Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandlerStackBuilderInterface;
use Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandlerStackProcessorInterface;
use Heptacom\HeptaConnect\Core\Web\Http\Dump\Contract\ServerRequestCycleDumpCheckerInterface;
use Heptacom\HeptaConnect\Core\Web\Http\Dump\Contract\ServerRequestCycleDumperInterface;
use Heptacom\HeptaConnect\Core\Web\Http\Handler\HttpMiddlewareChainHandler;
use Heptacom\HeptaConnect\Core\Web\Http\HttpHandleContext;
use Heptacom\HeptaConnect\Core\Web\Http\HttpHandlerStackProcessor;
use Heptacom\HeptaConnect\Core\Web\Http\HttpHandleService;
use Heptacom\HeptaConnect\Core\Web\Http\HttpMiddlewareHandler;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandleContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerCollection;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStack;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStackIdentifier;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\ServerRequestCycle;
use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\WebHttpHandlerConfigurationFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

#[CoversClass(LogMessage::class)]
#[CoversClass(AbstractPortalNodeContext::class)]
#[CoversClass(PortalNodeContainerFacade::class)]
#[CoversClass(HttpMiddlewareCollector::class)]
#[CoversClass(HttpMiddlewareChainHandler::class)]
#[CoversClass(HttpHandlerStackProcessor::class)]
#[CoversClass(HttpHandleService::class)]
#[CoversClass(HttpMiddlewareHandler::class)]
#[CoversClass(HttpHandlerContract::class)]
#[CoversClass(HttpHandlerStack::class)]
#[CoversClass(HttpHandlerStackIdentifier::class)]
#[CoversClass(ServerRequestCycle::class)]
#[CoversClass(WebHttpHandlerConfigurationFindCriteria::class)]
#[CoversClass(WebHttpHandlerConfigurationFindResult::class)]
#[CoversClass(AbstractCollection::class)]
final class HttpHandleServiceTest extends TestCase
{
    public function testActingFails(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(static::once())
            ->method('notice')
            ->with(LogMessage::WEB_HTTP_HANDLE_NO_HANDLER_FOR_PATH());

        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn('foobar');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUri')->willReturn($uri);
        $request->method('getAttributes')->willReturn([]);
        $request->method('withoutAttribute')->willReturnSelf();

        $response = $this->createMock(ResponseInterface::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $responseFactory->method('createResponse')->willReturn($response);
        $context = new HttpHandleContext($this->createMock(PortalNodeContainerFacadeContract::class), []);
        $contextFactory = $this->createMock(HttpHandleContextFactoryInterface::class);
        $contextFactory->method('createContext')->willReturn($context);
        $stackBuilder = $this->createMock(HttpHandlerStackBuilderInterface::class);
        $stackBuilderFactory = $this->createMock(HttpHandlerStackBuilderFactoryInterface::class);

        $stackBuilder->method('push')->willReturnSelf();
        $stackBuilder->method('pushSource')->willReturnSelf();
        $stackBuilder->method('pushDecorators')->willReturnSelf();
        $stackBuilder->expects(static::atLeastOnce())->method('isEmpty')->willReturn(true);
        $stackBuilderFactory->expects(static::atLeastOnce())->method('createHttpHandlerStackBuilder')->willReturn($stackBuilder);
        $response->expects(static::atLeastOnce())->method('withHeader')->willReturnSelf();

        $findAction = $this->createMock(WebHttpHandlerConfigurationFindActionInterface::class);
        $findAction->method('find')->willReturn(new WebHttpHandlerConfigurationFindResult([]));

        $dumpChecker = $this->createMock(ServerRequestCycleDumpCheckerInterface::class);
        $dumpChecker->method('shallDump')->willReturn(false);

        $dumper = $this->createMock(ServerRequestCycleDumperInterface::class);
        $dumper->expects(static::never())->method('dump');

        $httpHandlersFactory = $this->createMock(HttpHandleFlowHttpHandlersFactoryInterface::class);
        $httpHandlersFactory->method('createHttpHandlers')->willReturn(new HttpHandlerCollection());

        $stackProcessor = $this->createMock(HttpHandlerStackProcessorInterface::class);
        $stackProcessor->method('processStack')->willReturn($response);

        $service = new HttpHandleService(
            $stackProcessor,
            $contextFactory,
            $logger,
            $stackBuilderFactory,
            $this->createMock(StorageKeyGeneratorContract::class),
            $responseFactory,
            $findAction,
            $httpHandlersFactory,
            $dumpChecker,
            $dumper,
        );
        $service->handle($request, $portalNodeKey);
    }

    public function testHttpMiddlewares(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn('foobar');

        $isStackEmpty = false;

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUri')->willReturn($uri);
        $matcher = static::exactly(2);
        $request->expects($matcher)
            ->method('withAttribute')->willReturnCallback(function ($attributeName, $attributeValue) use ($matcher, $isStackEmpty, $request) {
                $parameters = [$attributeName, $attributeValue];

                match ($matcher->numberOfInvocations()) {
                    1 => self::assertEquals([HttpHandleContextInterface::REQUEST_ATTRIBUTE_IS_STACK_EMPTY, $isStackEmpty], $parameters),
                    2 => self::assertEquals(['Foo', 'Bar'], $parameters),
                };

                return $request;
            });
        $request->method('getAttributes')->willReturn([]);
        $request->method('withoutAttribute')->willReturnSelf();

        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeKey->method('withoutAlias')->willReturnSelf();

        $response = $this->createMock(ResponseInterface::class);
        $response->method('withHeader')->willReturnSelf();
        $response->expects(static::once())->method('withStatus')->with(200)->willReturnSelf();

        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $responseFactory->method('createResponse')->willReturn($response);

        $findAction = $this->createMock(WebHttpHandlerConfigurationFindActionInterface::class);
        $findAction->method('find')->willReturn(new WebHttpHandlerConfigurationFindResult([]));

        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $storageKeyGenerator->method('serialize')->willReturn('_');

        $stack = new HttpHandlerStack([
            new HttpMiddlewareChainHandler('', $isStackEmpty),
        ]);

        $stackBuilder = $this->createMock(HttpHandlerStackBuilderInterface::class);
        $stackBuilder->method('push')->willReturnSelf();
        $stackBuilder->method('pushSource')->willReturnSelf();
        $stackBuilder->method('pushDecorators')->willReturnSelf();
        $stackBuilder->method('isEmpty')->willReturn($isStackEmpty);
        $stackBuilder->method('build')->willReturn($stack);

        $stackBuilderFactory = $this->createMock(HttpHandlerStackBuilderFactoryInterface::class);
        $stackBuilderFactory->method('createHttpHandlerStackBuilder')->willReturn($stackBuilder);

        $logger = $this->createMock(LoggerInterface::class);

        $middleware = $this->createMock(MiddlewareInterface::class);
        $middleware->expects(static::once())->method('process')->willReturnCallback(
            function (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
                $request->withAttribute('Foo', 'Bar');

                $response = $handler->handle($request);

                return $response->withStatus(200);
            }
        );

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->willReturnCallback(function (string $id) use ($middleware) {
                if ($id === HttpMiddlewareCollector::class) {
                    return new HttpMiddlewareCollector([$middleware]);
                }

                return $this->createMock($id);
            });

        $context = new HttpHandleContext(new PortalNodeContainerFacade($container), []);
        $contextFactory = $this->createMock(HttpHandleContextFactoryInterface::class);
        $contextFactory->method('createContext')->willReturn($context);

        $httpHandleFlowHttpHandlersFactory = $this->createMock(HttpHandleFlowHttpHandlersFactoryInterface::class);
        $httpHandleFlowHttpHandlersFactory->method('createHttpHandlers')->willReturn(new HttpHandlerCollection());

        $dumpChecker = $this->createMock(ServerRequestCycleDumpCheckerInterface::class);
        $dumpChecker->method('shallDump')->willReturn(false);

        $dumper = $this->createMock(ServerRequestCycleDumperInterface::class);
        $dumper->expects(static::never())->method('dump');

        $service = new HttpHandleService(
            new HttpHandlerStackProcessor($logger),
            $contextFactory,
            $logger,
            $stackBuilderFactory,
            $this->createMock(StorageKeyGeneratorContract::class),
            $responseFactory,
            $findAction,
            $httpHandleFlowHttpHandlersFactory,
            $dumpChecker,
            $dumper,
        );

        $actualResponse = $service->handle($request, $portalNodeKey);

        static::assertSame($response, $actualResponse);
    }

    public function testDumpingOfRequestAndResponse(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn('foobar');

        $isStackEmpty = false;

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUri')->willReturn($uri);
        $matcher = static::exactly(2);
        $request->expects($matcher)
            ->method('withAttribute')->willReturnCallback(function ($attributeName, $attributeValue) use ($matcher, $isStackEmpty, $request) {
                $parameters = [$attributeName, $attributeValue];

                match ($matcher->numberOfInvocations()) {
                    1 => self::assertEquals([HttpHandleContextInterface::REQUEST_ATTRIBUTE_IS_STACK_EMPTY, $isStackEmpty], $parameters),
                    2 => self::assertEquals(['Foo', 'Bar'], $parameters),
                };

                return $request;
            });

        $request->method('getAttributes')->willReturn([]);
        $request->method('withoutAttribute')->willReturnSelf();

        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeKey->method('withoutAlias')->willReturnSelf();

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getReasonPhrase')->willReturn('Ok');
        $response->method('getStatusCode')->willReturn(200);
        $response->method('withHeader')->willReturnSelf();
        $response->expects(static::once())->method('withStatus')->with(200)->willReturnSelf();

        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $responseFactory->method('createResponse')->willReturn($response);

        $findAction = $this->createMock(WebHttpHandlerConfigurationFindActionInterface::class);
        $findAction->method('find')->willReturn(new WebHttpHandlerConfigurationFindResult([]));

        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $storageKeyGenerator->method('serialize')->willReturn('_');

        $stack = new HttpHandlerStack([
            new HttpMiddlewareChainHandler('', $isStackEmpty),
        ]);

        $stackBuilder = $this->createMock(HttpHandlerStackBuilderInterface::class);
        $stackBuilder->method('push')->willReturnSelf();
        $stackBuilder->method('pushSource')->willReturnSelf();
        $stackBuilder->method('pushDecorators')->willReturnSelf();
        $stackBuilder->method('isEmpty')->willReturn($isStackEmpty);
        $stackBuilder->method('build')->willReturn($stack);

        $stackBuilderFactory = $this->createMock(HttpHandlerStackBuilderFactoryInterface::class);
        $stackBuilderFactory->method('createHttpHandlerStackBuilder')->willReturn($stackBuilder);

        $logger = $this->createMock(LoggerInterface::class);

        $middleware = $this->createMock(MiddlewareInterface::class);
        $middleware->expects(static::once())->method('process')->willReturnCallback(
            function (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
                $request->withAttribute('Foo', 'Bar');

                $response = $handler->handle($request);

                return $response->withStatus(200);
            }
        );

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')
            ->willReturnCallback(function (string $id) use ($middleware) {
                if ($id === HttpMiddlewareCollector::class) {
                    return new HttpMiddlewareCollector([$middleware]);
                }

                return $this->createMock($id);
            });

        $context = new HttpHandleContext(new PortalNodeContainerFacade($container), []);
        $contextFactory = $this->createMock(HttpHandleContextFactoryInterface::class);
        $contextFactory->expects(static::once())->method('createContext')->willReturn($context);

        $httpHandleFlowHttpHandlersFactory = $this->createMock(HttpHandleFlowHttpHandlersFactoryInterface::class);
        $httpHandleFlowHttpHandlersFactory->method('createHttpHandlers')->willReturn(new HttpHandlerCollection());

        $dumpChecker = $this->createMock(ServerRequestCycleDumpCheckerInterface::class);
        $dumpChecker->method('shallDump')->willReturn(true);

        $dumper = $this->createMock(ServerRequestCycleDumperInterface::class);
        $dumper->expects(static::once())->method('dump');

        $service = new HttpHandleService(
            new HttpHandlerStackProcessor($logger),
            $contextFactory,
            $logger,
            $stackBuilderFactory,
            $storageKeyGenerator,
            $responseFactory,
            $findAction,
            $httpHandleFlowHttpHandlersFactory,
            $dumpChecker,
            $dumper,
        );

        $service->handle($request, $portalNodeKey);
    }
}
