<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Exploration;

use Heptacom\HeptaConnect\Core\Exploration\EmissionJobDispatchingExplorer;
use Heptacom\HeptaConnect\Core\Job\Contract\JobDispatcherContract;
use Heptacom\HeptaConnect\Core\Job\JobCollection;
use Heptacom\HeptaConnect\Core\Job\Transition\Contract\ExploredPrimaryKeysToJobsConverterInterface;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Exploration\AbstractBufferedResultProcessingExplorer
 * @covers \Heptacom\HeptaConnect\Core\Exploration\EmissionJobDispatchingExplorer
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
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
        $pks = $this->generatePrimaryKeys($batchSize * 5);

        $jobConverter
            ->expects(static::exactly(5))
            ->method('convert')
            ->willReturnCallback(static::validatePayloadForJobCountAndPks(\array_fill(0, 5, $batchSize), $pks));

        $explorer = new EmissionJobDispatchingExplorer($entityType, $jobConverter, $jobDispatcher, $logger, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls($pks, []);

        \iterable_to_array($explorer->explore($context, $stack));

        static::assertSame([], $pks);
    }

    public function testConvertsRemainingPksIfPkCountIsNotDividablePerfectlyByBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $jobConverter = $this->createMock(ExploredPrimaryKeysToJobsConverterInterface::class);
        $jobDispatcher = $this->createMock(JobDispatcherContract::class);
        $stack = $this->createMock(ExplorerStackInterface::class);
        $context = $this->createMock(ExploreContextInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $batchSize = 3;
        $pks = $this->generatePrimaryKeys($batchSize * 5 + 2);

        $context->method('getLogger')->willReturn($this->createMock(LoggerInterface::class));
        $jobConverter
            ->expects(static::exactly(6))
            ->method('convert')
            ->willReturnCallback(static::validatePayloadForJobCountAndPks([$batchSize, $batchSize, $batchSize, $batchSize, $batchSize, 2], $pks));

        $explorer = new EmissionJobDispatchingExplorer($entityType, $jobConverter, $jobDispatcher, $logger, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls($pks, []);

        \iterable_to_array($explorer->explore($context, $stack));

        static::assertSame([], $pks);
    }

    public function testConvertsPksToJobsAndDispatchesThemUntilAnExceptionIsThrown(): void
    {
        $entityType = FooBarEntity::class();
        $portalNodeLogger = $this->createMock(LoggerInterface::class);
        $jobConverter = $this->createMock(ExploredPrimaryKeysToJobsConverterInterface::class);
        $jobDispatcher = $this->createMock(JobDispatcherContract::class);
        $stack = $this->createMock(ExplorerStackInterface::class);
        $context = $this->createMock(ExploreContextInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $batchSize = 3;
        $pks = $this->generatePrimaryKeys($batchSize + 2);

        $context->method('getLogger')->willReturn($portalNodeLogger);
        $jobConverter
            ->expects(static::exactly(2))
            ->method('convert')
            ->willReturnCallback(static::validatePayloadForJobCountAndPks([$batchSize, 2], $pks));
        $jobDispatcher
            ->expects(static::exactly(2))
            ->method('dispatch');
        $portalNodeLogger->expects(static::once())->method('critical');

        $explorer = new EmissionJobDispatchingExplorer($entityType, $jobConverter, $jobDispatcher, $logger, $batchSize);

        $stack->method('next')->willReturnCallback(static function () use ($pks): iterable {
            yield from $pks;

            throw new \RuntimeException('Test message');
        });

        \iterable_to_array($explorer->explore($context, $stack));

        static::assertSame([], $pks);
    }

    private function generatePrimaryKeys(int $count): array
    {
        return \array_map('strval', \range(100, 100 + ($count - 1)));
    }

    /**
     * @param int[]    $batchSizes
     * @param string[] $primaryKeys
     */
    private function validatePayloadForJobCountAndPks(array $batchSizes, array &$primaryKeys): \Closure
    {
        return static function (
            PortalNodeKeyInterface $portalNodeKey,
            EntityType $entityType,
            StringCollection $externalIds
        ) use (&$primaryKeys, &$batchSizes): JobCollection {
            $batchSize = \array_shift($batchSizes);

            static::assertIsInt($batchSize);
            static::assertCount($batchSize, $externalIds);

            foreach ($externalIds as $externalId) {
                static::assertNotNull($externalId);
                static::assertContains($externalId, $primaryKeys);

                $primaryKeys = \array_diff($primaryKeys, [$externalId]);
            }

            return new JobCollection();
        };
    }
}
