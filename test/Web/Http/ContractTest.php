<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Web\Http;

use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandleContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStack;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStack
 */
class ContractTest extends TestCase
{
    public function testImplementation(): void
    {
        $handler = new class() extends HttpHandlerContract {
            protected function supports(): string
            {
                return '/foobar';
            }

            protected function run(
                ServerRequestInterface $request,
                ResponseInterface $response,
                HttpHandleContextInterface $context
            ): ResponseInterface {
                return parent::run($request, $response, $context);
            }

            protected function options(
                ServerRequestInterface $request,
                ResponseInterface $response,
                HttpHandleContextInterface $context
            ): ResponseInterface {
                return parent::options($request, $response, $context);
            }

            protected function get(
                ServerRequestInterface $request,
                ResponseInterface $response,
                HttpHandleContextInterface $context
            ): ResponseInterface {
                return parent::get($request, $response, $context);
            }

            protected function post(
                ServerRequestInterface $request,
                ResponseInterface $response,
                HttpHandleContextInterface $context
            ): ResponseInterface {
                return parent::post($request, $response, $context);
            }

            protected function put(
                ServerRequestInterface $request,
                ResponseInterface $response,
                HttpHandleContextInterface $context
            ): ResponseInterface {
                return parent::put($request, $response, $context);
            }

            protected function patch(
                ServerRequestInterface $request,
                ResponseInterface $response,
                HttpHandleContextInterface $context
            ): ResponseInterface {
                return parent::patch($request, $response, $context);
            }

            protected function delete(
                ServerRequestInterface $request,
                ResponseInterface $response,
                HttpHandleContextInterface $context
            ): ResponseInterface {
                return parent::delete($request, $response, $context);
            }
        };

        $response = Psr17FactoryDiscovery::findResponseFactory()->createResponse(501);
        self::assertSame('foobar', $handler->getPath());
        self::assertSame($response, $handler->handle(
            Psr17FactoryDiscovery::findServerRequestFactory()->createServerRequest('GET', '/foobar'),
            $response,
            $this->createMock(HttpHandleContextInterface::class),
            new HttpHandlerStack([$handler])
        ));
    }

    public function testLogErrors(): void
    {
        $handler = new class() extends HttpHandlerContract {
            protected function run(
                ServerRequestInterface $request,
                ResponseInterface $response,
                HttpHandleContextInterface $context
            ): ResponseInterface {
                throw new \RuntimeException('Oh nose');
            }

            protected function supports(): string
            {
                return 'foobar';
            }
        };

        $logger = $this->createMock(LoggerInterface::class);
        $context = $this->createMock(HttpHandleContextInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $context->method('getContainer')->willReturn($container);

        $container->method('get')->willReturnCallback(static fn(string $id) => [
            LoggerInterface::class => $logger,
        ][$id] ?? null);

        $logger->expects(self::once())->method('error');
        self::assertSame('foobar', $handler->getPath());

        try {
            $handler->handle(
                Psr17FactoryDiscovery::findServerRequestFactory()->createServerRequest('GET', '/foobar'),
                Psr17FactoryDiscovery::findResponseFactory()->createResponse(501),
                $context,
                new HttpHandlerStack([$handler])
            );
            self::fail('We expect an exception');
        } catch (\Throwable $throwable) {
            self::assertSame('Oh nose', $throwable->getMessage());
        }
    }
}
