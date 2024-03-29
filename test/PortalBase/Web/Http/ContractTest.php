<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Web\Http;

use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandleContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStack;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStack
 */
final class ContractTest extends TestCase
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
        static::assertSame('foobar', $handler->getPath());
        static::assertSame($response, $handler->handle(
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
        $context->method('getLogger')->willReturn($logger);

        $logger->expects(static::once())->method('error');
        static::assertSame('foobar', $handler->getPath());

        try {
            $handler->handle(
                Psr17FactoryDiscovery::findServerRequestFactory()->createServerRequest('GET', '/foobar'),
                Psr17FactoryDiscovery::findResponseFactory()->createResponse(501),
                $context,
                new HttpHandlerStack([$handler])
            );
            static::fail('We expect an exception');
        } catch (\Throwable $throwable) {
            static::assertSame('Oh nose', $throwable->getMessage());
        }
    }
}
