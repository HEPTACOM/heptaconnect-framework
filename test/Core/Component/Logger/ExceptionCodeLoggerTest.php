<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Component\Logger;

use Heptacom\HeptaConnect\Core\Component\Logger\ExceptionCodeLogger;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

#[CoversClass(ExceptionCodeLogger::class)]
final class ExceptionCodeLoggerTest extends TestCase
{
    public function testExceptionLogContainsTypeAndCode(): void
    {
        $logger = new class() extends AbstractLogger {
            public array $logs = [];

            public function log($level, $message, array $context = []): void
            {
                $this->logs[$level] = $message;
            }
        };
        $decorator = new ExceptionCodeLogger($logger);
        $exception = new \Exception('test', 100);
        $error = new \Error('test', 200);
        $decorator->log(LogLevel::ALERT, 'test_none', ['string', 1, 1.0]);
        $decorator->log(LogLevel::CRITICAL, 'test_exception_100', ['string', 1, $exception, 1.0]);
        $decorator->log(LogLevel::DEBUG, 'test_error_200', ['string', 1, $error, 1.0]);
        $decorator->log(LogLevel::NOTICE, 'test_both_100_200', ['string', $exception, 1, 1.0, $error]);

        static::assertEquals('test_none', $logger->logs[LogLevel::ALERT]);
        static::assertStringContainsString('[Exception Code: 100]', $logger->logs[LogLevel::CRITICAL]);
        static::assertStringContainsString('test_exception_100', $logger->logs[LogLevel::CRITICAL]);
        static::assertStringContainsString('[Error Code: 200]', $logger->logs[LogLevel::DEBUG]);
        static::assertStringContainsString('test_error_200', $logger->logs[LogLevel::DEBUG]);
        static::assertStringContainsString('[Exception Code: 100]', $logger->logs[LogLevel::NOTICE]);
        static::assertStringContainsString('test_both_100_200', $logger->logs[LogLevel::NOTICE]);
        static::assertStringContainsString('[Error Code: 200]', $logger->logs[LogLevel::NOTICE]);
    }
}
