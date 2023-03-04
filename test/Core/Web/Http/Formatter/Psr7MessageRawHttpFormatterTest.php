<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Web\Http\Formatter;

use Heptacom\HeptaConnect\Core\Web\Http\Formatter\Psr7MessageRawHttpFormatter;
use Heptacom\HeptaConnect\Core\Web\Http\Formatter\Support\HeaderUtility;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\Formatter\Psr7MessageRawHttpFormatter
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\Formatter\Support\HeaderUtility
 */
final class Psr7MessageRawHttpFormatterTest extends TestCase
{
    public function testHeadersAreInAlphabeticalOrder(): void
    {
        $headerUtility = new HeaderUtility();
        $formatter = new Psr7MessageRawHttpFormatter($headerUtility);

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

    public function testFormatterFailsOnMessageThatIsNeitherRequestNorResponse(): void
    {
        $headerUtility = new HeaderUtility();
        $formatter = new Psr7MessageRawHttpFormatter($headerUtility);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1674950000);

        $formatter->formatMessage($this->createMock(MessageInterface::class));
    }

    public function testFormatterFileExtensionSuggestionFailsOnMessageThatIsNeitherRequestNorResponse(): void
    {
        $headerUtility = new HeaderUtility();
        $formatter = new Psr7MessageRawHttpFormatter($headerUtility);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode(1674950001);

        $formatter->getFileExtension($this->createMock(MessageInterface::class));
    }
}
