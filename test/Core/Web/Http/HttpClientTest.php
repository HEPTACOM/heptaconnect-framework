<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Web\Http;

use Heptacom\HeptaConnect\Core\Web\Http\HttpClient;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\HttpClient
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpClientContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\Exception\HttpException
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\Support\DefaultRequestHeaders
 */
class HttpClientTest extends TestCase
{
    public function testHeaders(): void
    {
        $client = new class() implements ClientInterface {
            public array $headers = [];

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                $this->headers[] = $request->getHeaders();

                return Psr17FactoryDiscovery::findResponseFactory()->createResponse();
            }
        };

        $uriFactory = Psr17FactoryDiscovery::findUriFactory();
        $requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $httpClient = new HttpClient($client, $uriFactory);
        $httpClient = $httpClient->withDefaultRequestHeaders(
            $httpClient->getDefaultRequestHeaders()
                ->withHeader('foo', ['bar'])
                ->withAddedHeader('gizmo', ['fiddle'])
                ->withAddedHeader('time', ['now'])
                ->withoutHeader('time')
        );
        $httpClient->sendRequest($requestFactory->createRequest('GET', $uriFactory->createUri('https://dev.null/')));
        $httpClient->sendRequest(
            $requestFactory->createRequest('GET', $uriFactory->createUri('https://dev.null/'))
                ->withHeader('foo', ['ninja'])
                ->withHeader('gizmo', ['ninja'])
        );

        static::assertSame([[
            'Host' => ['dev.null'],
            'foo' => ['bar'],
            'gizmo' => ['fiddle'],
        ], [
            'Host' => ['dev.null'],
            'foo' => ['ninja'],
            'gizmo' => ['ninja'],
        ]], $client->headers);
    }

    public function testRetries(): void
    {
        $client = new class() implements ClientInterface {
            public int $count = 0;

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                ++$this->count;

                return Psr17FactoryDiscovery::findResponseFactory()
                    ->createResponse(429)
                    ->withHeader('Retry-After', 5);
            }
        };

        $uriFactory = Psr17FactoryDiscovery::findUriFactory();
        $requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $httpClient = new HttpClient($client, $uriFactory);
        $httpClient = $httpClient->withMaxRetry(3)->withMaxWaitTimeout();
        $start = time();

        $httpClient->sendRequest($requestFactory->createRequest('GET', $uriFactory->createUri('https://dev.null/')));

        $end = time();

        static::assertSame(4, $client->count);
        static::assertGreaterThanOrEqual(15, $end - $start);
        static::assertLessThanOrEqual(17, $end - $start);
    }

    public function testRedirect(): void
    {
        $client = new class() implements ClientInterface {
            public string $targetA = 'http://dev.null/';

            public string $targetB = 'http://dev.urandom/';

            public string $targetC = 'http://dev.random/';

            public int $count = 0;

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                $target = [
                    $this->targetA => $this->targetB,
                    $this->targetB => $this->targetC,
                    $this->targetC => $this->targetA,
                ][$request->getUri()->__toString()];

                ++$this->count;

                return Psr17FactoryDiscovery::findResponseFactory()
                    ->createResponse(307)
                    ->withHeader('Location', $target);
            }
        };

        $uriFactory = Psr17FactoryDiscovery::findUriFactory();
        $requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $httpClient = new HttpClient($client, $uriFactory);
        $httpClient = $httpClient->withMaxRedirect(10);

        $response = $httpClient->sendRequest($requestFactory->createRequest('GET', $uriFactory->createUri($client->targetA)));

        static::assertSame(11, $client->count);
        static::assertSame($client->targetC, $response->getHeader('Location')[0]);
    }

    public function testThrow(): void
    {
        $client = new class() implements ClientInterface {
            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                return Psr17FactoryDiscovery::findResponseFactory()->createResponse(503);
            }
        };

        $uriFactory = Psr17FactoryDiscovery::findUriFactory();
        $requestFactory = Psr17FactoryDiscovery::findRequestFactory();
        $httpClient = new HttpClient($client, $uriFactory);
        $httpClient = $httpClient->withExceptionTriggers(...\range(500, 599));

        self::expectExceptionMessageMatches('/503/');
        $httpClient->sendRequest($requestFactory->createRequest('GET', $uriFactory->createUri('http://dev.null')));
    }
}
