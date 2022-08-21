<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Exploration;

use Heptacom\HeptaConnect\Core\Exploration\EmissionJobDispatchingExplorer;
use Heptacom\HeptaConnect\Core\Job\Contract\JobDispatcherContract;
use Heptacom\HeptaConnect\Core\Job\Transition\Contract\ExploredPrimaryKeysToJobsConverterInterface;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\Constraint\IsNull;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Exploration\EmissionJobDispatchingExplorer
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract
 */
final class EmissionJobDispatchingExplorerTest extends TestCase
{
    public function testConvertsPksInBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $jobConverter = $this->createMock(ExploredPrimaryKeysToJobsConverterInterface::class);
        $jobDispatcher = $this->createMock(JobDispatcherContract::class);
        $stack = $this->createMock(ExplorerStackInterface::class);
        $context = $this->createMock(ExploreContextInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $batchSize = 3;

        $jobConverter
            ->expects(static::exactly(5))
            ->method('convert')
            ->with(new LogicalNot(new IsNull()), new LogicalNot(new IsNull()), new Count($batchSize));

        $explorer = new EmissionJobDispatchingExplorer($entityType, $jobConverter, $jobDispatcher, $logger, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls(
            \array_map('strval', \array_keys(\array_fill(0, $batchSize * 5, 0))),
            []
        );

        \iterable_to_array($explorer->explore($context, $stack));
    }

    public function testConvertsRemainingPksIfPkCountIsNotDividablePerfectlyByBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $container = $this->createMock(ContainerInterface::class);
        $jobConverter = $this->createMock(ExploredPrimaryKeysToJobsConverterInterface::class);
        $jobDispatcher = $this->createMock(JobDispatcherContract::class);
        $stack = $this->createMock(ExplorerStackInterface::class);
        $context = $this->createMock(ExploreContextInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $batchSize = 3;

        $container->method('get')->willReturnCallback(fn (string $id) => [
            LoggerInterface::class => $this->createMock(LoggerInterface::class),
        ][$id] ?? null);
        $context->method('getContainer')->willReturn($container);
        $jobConverter
            ->expects(static::exactly(6))
            ->method('convert')
            ->withConsecutive([
                new LogicalNot(new IsNull()), new LogicalNot(new IsNull()), new Count($batchSize),
            ], [
                new LogicalNot(new IsNull()), new LogicalNot(new IsNull()), new Count($batchSize),
            ], [
                new LogicalNot(new IsNull()), new LogicalNot(new IsNull()), new Count($batchSize),
            ], [
                new LogicalNot(new IsNull()), new LogicalNot(new IsNull()), new Count($batchSize),
            ], [
                new LogicalNot(new IsNull()), new LogicalNot(new IsNull()), new Count($batchSize),
            ], [
                new LogicalNot(new IsNull()), new LogicalNot(new IsNull()), new Count(2),
            ]);

        $explorer = new EmissionJobDispatchingExplorer($entityType, $jobConverter, $jobDispatcher, $logger, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls(
            \array_map('strval', \array_keys(\array_fill(0, $batchSize * 5 + 2, 0))),
            []
        );

        \iterable_to_array($explorer->explore($context, $stack));
    }

    public function testConvertsPksToJobsAndDispatchesThemUntilAnExceptionIsThrown(): void
    {
        $entityType = FooBarEntity::class();
        $container = $this->createMock(ContainerInterface::class);
        $portalNodeLogger = $this->createMock(LoggerInterface::class);
        $jobConverter = $this->createMock(ExploredPrimaryKeysToJobsConverterInterface::class);
        $jobDispatcher = $this->createMock(JobDispatcherContract::class);
        $stack = $this->createMock(ExplorerStackInterface::class);
        $context = $this->createMock(ExploreContextInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $batchSize = 3;

        $container->method('get')->willReturnCallback(fn (string $id) => [
            LoggerInterface::class => $portalNodeLogger,
        ][$id] ?? null);
        $context->method('getContainer')->willReturn($container);
        $jobConverter
            ->expects(static::exactly(2))
            ->method('convert')
            ->withConsecutive([
                new LogicalNot(new IsNull()), new LogicalNot(new IsNull()), new Count($batchSize),
            ], [
                new LogicalNot(new IsNull()), new LogicalNot(new IsNull()), new Count(2),
            ]);
        $jobDispatcher
            ->expects(static::exactly(2))
            ->method('dispatch');
        $portalNodeLogger->expects(static::once())->method('critical');

        $explorer = new EmissionJobDispatchingExplorer($entityType, $jobConverter, $jobDispatcher, $logger, $batchSize);

        $stack->method('next')->willReturnCallback(static function () use ($batchSize): iterable {
            yield from \array_map('strval', \array_keys(\array_fill(0, $batchSize + 2, 0)));

            throw new \RuntimeException('Test message');
        });

        \iterable_to_array($explorer->explore($context, $stack));
    }
}
