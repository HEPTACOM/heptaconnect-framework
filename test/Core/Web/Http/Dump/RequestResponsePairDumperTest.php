<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Web\Http\Dump;

use Heptacom\HeptaConnect\Core\Bridge\File\HttpHandlerDumpPathProviderInterface;
use Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandleServiceInterface;
use Heptacom\HeptaConnect\Core\Web\Http\Dump\RequestResponsePairDumper;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\Psr7MessageFormatterContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStackIdentifier;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\Dump\RequestResponsePairDumper
 * @covers \Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerStackIdentifier
 */
final class RequestResponsePairDumperTest extends TestCase
{
    private const DUMP_DIR = __DIR__ . '/../../../Fixture/_files/http_handle_dump_dir';

    protected function setUp(): void
    {
        parent::setUp();

        foreach ($this->getDumpedFiles() as $file) {
            \unlink(static::DUMP_DIR . '/' . $file);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        foreach ($this->getDumpedFiles() as $file) {
            \unlink(static::DUMP_DIR . '/' . $file);
        }
    }

    public function testMessagesGetDumped(): void
    {
        $pathProvider = $this->createPathProvider();

        $formatter = $this->createMessageFormatter();

        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeKey->method('withoutAlias')->willReturnSelf();

        $requestFactory = Psr17FactoryDiscovery::findServerRequestFactory();
        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();

        $dumper = new RequestResponsePairDumper($pathProvider, $formatter);
        $dumper->dump(
            new HttpHandlerStackIdentifier($portalNodeKey, 'foo-bar'),
            $requestFactory->createServerRequest('GET', 'http://127.0.0.1/folder/foo-bar'),
            $responseFactory->createResponse(404)
        );

        static::assertCount(2, $this->getDumpedFiles());
    }

    public function testOriginalRequestGetsDumpedInstead(): void
    {
        $pathProvider = $this->createPathProvider();

        $formatter = $this->createMessageFormatter();

        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeKey->method('withoutAlias')->willReturnSelf();

        $requestFactory = Psr17FactoryDiscovery::findServerRequestFactory();
        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();

        $dumper = new RequestResponsePairDumper($pathProvider, $formatter);
        $dumper->dump(
            new HttpHandlerStackIdentifier($portalNodeKey, 'foo-bar'),
            $requestFactory
                ->createServerRequest('GET', 'http://127.0.0.1/folder/foo-bar')
                ->withAttribute(
                    HttpHandleServiceInterface::REQUEST_ATTRIBUTE_ORIGINAL_REQUEST,
                    $requestFactory->createServerRequest('GET', 'http://foo-bar.test/complex-path')
                ),
            $responseFactory->createResponse(404)
        );

        static::assertCount(2, $this->getDumpedFiles());
        static::assertStringContainsString('http://foo-bar.test/complex-path', \file_get_contents(static::DUMP_DIR . '/-.request.txt'));
    }

    public function testMessagesGetDumpedWithCorrelationId(): void
    {
        $pathProvider = $this->createPathProvider();

        $formatter = $this->createMessageFormatter();

        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeKey->method('withoutAlias')->willReturnSelf();

        $requestFactory = Psr17FactoryDiscovery::findServerRequestFactory();
        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();

        $correlationId = 'correlation-809e698b-eb90-4cf0-9076-9dbaa57ba99c';
        $response = $responseFactory->createResponse(404)->withHeader('X-HeptaConnect-Correlation-Id', $correlationId);

        $dumper = new RequestResponsePairDumper($pathProvider, $formatter);
        $dumper->dump(
            new HttpHandlerStackIdentifier($portalNodeKey, 'foo-bar'),
            $requestFactory->createServerRequest('GET', 'http://127.0.0.1/folder/foo-bar'),
            $response
        );

        static::assertCount(2, $this->getDumpedFiles());

        foreach ($this->getDumpedFiles() as $filename) {
            static::assertStringContainsString($correlationId, $filename);
        }
    }

    private function getDumpedFiles(): array
    {
        $files = \scandir(static::DUMP_DIR);

        static::assertNotFalse($files);

        return \array_values(\array_diff($files, [
            '.',
            '..',
            '.gitignore',
        ]));
    }

    private function createMessageFormatter(): Psr7MessageFormatterContract
    {
        $formatter = $this->createMock(Psr7MessageFormatterContract::class);
        $formatter->expects(static::exactly(2))->method('formatMessage')->willReturnCallback(
            static function (MessageInterface $message): string {
                if ($message instanceof RequestInterface) {
                    static::assertFalse($message->hasHeader(HttpHandleServiceInterface::REQUEST_ATTRIBUTE_ORIGINAL_REQUEST));

                    return (string) $message->getUri();
                }

                if ($message instanceof ResponseInterface) {
                    return $message->getStatusCode() . ' ' . $message->getReasonPhrase();
                }

                static::fail('Invalid argument');
            }
        );
        $formatter->expects(static::exactly(2))->method('getFileExtension')->willReturnCallback(
            static function (MessageInterface $message): string {
                if ($message instanceof RequestInterface) {
                    return 'request.txt';
                }

                if ($message instanceof ResponseInterface) {
                    return 'response.txt';
                }

                static::fail('Invalid argument');
            }
        );

        return $formatter;
    }

    private function createPathProvider(): HttpHandlerDumpPathProviderInterface
    {
        $pathProvider = $this->createMock(HttpHandlerDumpPathProviderInterface::class);
        $pathProvider->expects(static::once())->method('provide')->willReturn(static::DUMP_DIR . '/');

        return $pathProvider;
    }
}
