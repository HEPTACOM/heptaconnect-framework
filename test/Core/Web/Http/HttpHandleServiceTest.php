<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Web\Http;

use Heptacom\HeptaConnect\Core\Component\LogMessage;
use Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandleContextFactoryInterface;
use Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandlerStackBuilderFactoryInterface;
use Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandlerStackBuilderInterface;
use Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandlingActorInterface;
use Heptacom\HeptaConnect\Core\Web\Http\HttpHandleContext;
use Heptacom\HeptaConnect\Core\Web\Http\HttpHandleService;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\WebHttpHandlerConfigurationFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Component\LogMessage
 * @covers \Heptacom\HeptaConnect\Core\Portal\AbstractPortalNodeContext
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\HttpHandleService
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindCriteria
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\WebHttpHandlerConfiguration\Find\WebHttpHandlerConfigurationFindResult
 */
class HttpHandleServiceTest extends TestCase
{
    public function testActingFails(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(static::once())
            ->method('critical')
            ->with(LogMessage::WEB_HTTP_HANDLE_NO_HANDLER_FOR_PATH());

        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $uri = $this->createMock(UriInterface::class);
        $uri->method('getPath')->willReturn('foobar');
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUri')->willReturn($uri);
        $response = $this->createMock(ResponseInterface::class);
        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $responseFactory->method('createResponse')->willReturn($response);
        $context = new HttpHandleContext($this->createMock(ContainerInterface::class), []);
        $contextFactory = $this->createMock(HttpHandleContextFactoryInterface::class);
        $contextFactory->method('createContext')->willReturn($context);
        $stackBuilder = $this->createMock(HttpHandlerStackBuilderInterface::class);
        $stackBuilderFactory = $this->createMock(HttpHandlerStackBuilderFactoryInterface::class);

        $stackBuilder->method('pushSource')->willReturnSelf();
        $stackBuilder->method('pushDecorators')->willReturnSelf();
        $stackBuilder->expects(static::atLeastOnce())->method('isEmpty')->willReturn(true);
        $stackBuilderFactory->expects(static::atLeastOnce())->method('createHttpHandlerStackBuilder')->willReturn($stackBuilder);
        $response->expects(static::atLeastOnce())->method('withHeader')->willReturnSelf();

        $findAction = $this->createMock(WebHttpHandlerConfigurationFindActionInterface::class);
        $findAction->method('find')->willReturn(new WebHttpHandlerConfigurationFindResult([]));

        $service = new HttpHandleService(
            $this->createMock(HttpHandlingActorInterface::class),
            $contextFactory,
            $logger,
            $stackBuilderFactory,
            $this->createMock(StorageKeyGeneratorContract::class),
            $responseFactory,
            $findAction,
        );
        $service->handle($request, $portalNodeKey);
    }
}
