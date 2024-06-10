<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Web\Http\Formatter;

use Heptacom\HeptaConnect\Core\Web\Http\Formatter\Psr7MessageCurlShellFormatter;
use Heptacom\HeptaConnect\Core\Web\Http\Formatter\Psr7MessageRawHttpFormatter;
use Heptacom\HeptaConnect\Core\Web\Http\Formatter\Support\HeaderUtility;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;

#[CoversClass(Psr7MessageCurlShellFormatter::class)]
#[CoversClass(Psr7MessageRawHttpFormatter::class)]
#[CoversClass(HeaderUtility::class)]
final class Psr7MessageCurlShellFormatterTest extends TestCase
{
    public function testHeadersAreInAlphabeticalOrder(): void
    {
        $headerUtility = new HeaderUtility();
        $formatter = new Psr7MessageCurlShellFormatter(
            $headerUtility,
            new Psr7MessageRawHttpFormatter($headerUtility),
            'curl'
        );

        $request = Psr17FactoryDiscovery::findServerRequestFactory()->createServerRequest('GET', 'https://example.com');
        $request = $request->withHeader('X-HeptaConnect-Test-1', 'test1');
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withHeader('X-HeptaConnect-Test', 'test');
        $request = $request->withHeader('X-Forwarded-For', '127.0.0.1');
        $request = $request->withHeader('Host', 'example.com');

        $command = $formatter->formatMessage($request);

        $positionHost = \mb_stripos($command, 'Host: example.com');
        $positionContentType = \mb_stripos($command, 'Content-Type: application/json');
        $positionXForwardedFor = \mb_stripos($command, 'X-Forwarded-For: 127.0.0.1');
        $positionXHeptaConnectTest = \mb_stripos($command, 'X-HeptaConnect-Test: test');
        $positionXHeptaConnectTest1 = \mb_stripos($command, 'X-HeptaConnect-Test-1: test1');

        static::assertNotFalse($positionHost);
        static::assertNotFalse($positionContentType);
        static::assertNotFalse($positionXForwardedFor);
        static::assertNotFalse($positionXHeptaConnectTest);
        static::assertNotFalse($positionXHeptaConnectTest1);

        static::assertGreaterThan($positionHost, $positionContentType, 'Content-Type should be after Host');
        static::assertGreaterThan($positionContentType, $positionXForwardedFor, 'X-Forwarded-For should be after Content-Type');
        static::assertGreaterThan($positionXForwardedFor, $positionXHeptaConnectTest, 'X-HeptaConnect-Test should be after X-Forwarded-For');
        static::assertGreaterThan($positionXHeptaConnectTest, $positionXHeptaConnectTest1, 'X-HeptaConnect-Test-1 should be after X-HeptaConnect-Test');
    }

    public function testEachShellArgumentIsOnNewLine(): void
    {
        $headerUtility = new HeaderUtility();
        $formatter = new Psr7MessageCurlShellFormatter(
            $headerUtility,
            new Psr7MessageRawHttpFormatter($headerUtility),
            'curl'
        );

        $request = Psr17FactoryDiscovery::findServerRequestFactory()->createServerRequest('GET', 'https://example.com');
        $request = $request->withHeader('X-HeptaConnect-Test-1', 'test1');
        $request = $request->withProtocolVersion('1.1');
        $command = $formatter->formatMessage($request);

        static::assertSame(8, \preg_match_all('/^\s+-/m', $command, $matches, \PREG_OFFSET_CAPTURE));
    }

    public function testHttpProtocolVersionIsRepresentedByCorrectCurlParameter(): void
    {
        $headerUtility = new HeaderUtility();
        $formatter = new Psr7MessageCurlShellFormatter(
            $headerUtility,
            new Psr7MessageRawHttpFormatter($headerUtility),
            'curl'
        );

        $request = Psr17FactoryDiscovery::findServerRequestFactory()->createServerRequest('GET', 'https://example.com');
        $command = $formatter->formatMessage($request->withProtocolVersion('0.9'));

        static::assertStringContainsString('-http0.9', $command);

        $command = $formatter->formatMessage($request->withProtocolVersion('1.0'));

        static::assertStringContainsString('-http1.0', $command);

        $command = $formatter->formatMessage($request->withProtocolVersion('1.1'));

        static::assertStringContainsString('-http1.1', $command);

        $command = $formatter->formatMessage($request->withProtocolVersion('2.0'));

        static::assertStringContainsString('-http2', $command);

        $command = $formatter->formatMessage($request->withProtocolVersion('3.0'));

        static::assertStringContainsString('-http3', $command);
    }

    public function testFormatterFailsOnMessageThatIsNeitherRequestNorResponse(): void
    {
        $headerUtility = new HeaderUtility();
        $formatter = new Psr7MessageCurlShellFormatter(
            $headerUtility,
            new Psr7MessageRawHttpFormatter($headerUtility),
            'curl'
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1674950002);

        $formatter->formatMessage($this->createMock(MessageInterface::class));
    }

    public function testFormatterFileExtensionSuggestionFailsOnMessageThatIsNeitherRequestNorResponse(): void
    {
        $headerUtility = new HeaderUtility();
        $formatter = new Psr7MessageCurlShellFormatter(
            $headerUtility,
            new Psr7MessageRawHttpFormatter($headerUtility),
            'curl'
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1674950003);

        $formatter->getFileExtension($this->createMock(MessageInterface::class));
    }
}
