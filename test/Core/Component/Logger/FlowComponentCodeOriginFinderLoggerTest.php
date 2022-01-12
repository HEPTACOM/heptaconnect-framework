<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Component\Logger;

use Heptacom\HeptaConnect\Core\Component\Logger\FlowComponentCodeOriginFinderLogger;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\StatusReporting\Contract\StatusReporterContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerCodeOriginFinderInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpHandlerContract;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

/**
 * @covers \Heptacom\HeptaConnect\Core\Component\Logger\FlowComponentCodeOriginFinderLogger
 * @covers \Heptacom\HeptaConnect\Portal\Base\FlowComponent\CodeOrigin
 */
class FlowComponentCodeOriginFinderLoggerTest extends TestCase
{
    public function testLocateComponentInContext(): void
    {
        $emitterCodeOriginFinder = $this->createMock(EmitterCodeOriginFinderInterface::class);
        $explorerCodeOriginFinder = $this->createMock(ExplorerCodeOriginFinderInterface::class);
        $receiverCodeOriginFinder = $this->createMock(ReceiverCodeOriginFinderInterface::class);
        $statusReporterCodeOriginFinder = $this->createMock(StatusReporterCodeOriginFinderInterface::class);
        $httpHandlerCodeOriginFinder = $this->createMock(HttpHandlerCodeOriginFinderInterface::class);
        $logger = new class() extends AbstractLogger {
            public array $logs = [];

            public function log($level, $message, array $context = []): void
            {
                $this->logs[$level][$message][] = $context;
            }
        };

        $codeOrigin = new CodeOrigin(__FILE__, __LINE__, __LINE__);
        $emitterCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);
        $explorerCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);
        $receiverCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);
        $statusReporterCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);
        $httpHandlerCodeOriginFinder->expects(static::once())->method('findOrigin')->willReturn($codeOrigin);

        $decorator = new FlowComponentCodeOriginFinderLogger(
            $logger,
            $emitterCodeOriginFinder,
            $explorerCodeOriginFinder,
            $receiverCodeOriginFinder,
            $statusReporterCodeOriginFinder,
            $httpHandlerCodeOriginFinder
        );

        $decorator->log(LogLevel::CRITICAL, 'test', [
            'emitter' => $this->createMock(EmitterContract::class),
            'explorer' => $this->createMock(ExplorerContract::class),
            'receiver' => $this->createMock(ReceiverContract::class),
            'statusReporter' => $this->createMock(StatusReporterContract::class),
            'httpHandler' => $this->createMock(HttpHandlerContract::class),
        ]);

        static::assertSame((string) $codeOrigin, $logger->logs[LogLevel::CRITICAL]['test'][0]['emitter']);
        static::assertSame((string) $codeOrigin, $logger->logs[LogLevel::CRITICAL]['test'][0]['explorer']);
        static::assertSame((string) $codeOrigin, $logger->logs[LogLevel::CRITICAL]['test'][0]['receiver']);
        static::assertSame((string) $codeOrigin, $logger->logs[LogLevel::CRITICAL]['test'][0]['statusReporter']);
        static::assertSame((string) $codeOrigin, $logger->logs[LogLevel::CRITICAL]['test'][0]['httpHandler']);
    }
}
