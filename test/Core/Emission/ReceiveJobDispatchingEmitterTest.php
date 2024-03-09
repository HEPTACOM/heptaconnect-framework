<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Emission;

use Heptacom\HeptaConnect\Core\Emission\ReceiveJobDispatchingEmitter;
use Heptacom\HeptaConnect\Core\Job\Contract\JobDispatcherContract;
use Heptacom\HeptaConnect\Core\Job\JobCollection;
use Heptacom\HeptaConnect\Core\Job\Transition\Contract\EmittedEntitiesToJobsConverterInterface;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Emission\AbstractBufferedResultProcessingEmitter
 * @covers \Heptacom\HeptaConnect\Core\Emission\ReceiveJobDispatchingEmitter
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\DependencyTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract
 */
final class ReceiveJobDispatchingEmitterTest extends TestCase
{
    public function testConvertsEntitiesInBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $jobConverter = $this->createMock(EmittedEntitiesToJobsConverterInterface::class);
        $jobDispatcher = $this->createMock(JobDispatcherContract::class);
        $stack = $this->createMock(EmitterStackInterface::class);
        $context = $this->createMock(EmitContextInterface::class);
        $batchSize = 3;
        $pks = $this->generatePrimaryKeys($batchSize * 5);
        $entities = $this->generateEntities($pks);

        $jobConverter
            ->expects(static::exactly(5))
            ->method('convert')
            ->willReturnCallback(static::validatePayloadForJobCountAndPks(\array_fill(0, 5, $batchSize), $pks));

        $emitter = new ReceiveJobDispatchingEmitter($entityType, $jobConverter, $jobDispatcher, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls($entities, []);

        \iterable_to_array($emitter->emit($pks, $context, $stack));

        static::assertSame([], $pks);
    }

    public function testConvertsRemainingEntitiesIfEntityCountIsNotDividablePerfectlyByBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $jobConverter = $this->createMock(EmittedEntitiesToJobsConverterInterface::class);
        $jobDispatcher = $this->createMock(JobDispatcherContract::class);
        $stack = $this->createMock(EmitterStackInterface::class);
        $context = $this->createMock(EmitContextInterface::class);
        $batchSize = 3;
        $pks = $this->generatePrimaryKeys($batchSize * 5 + 2);
        $entities = $this->generateEntities($pks);

        $jobConverter
            ->expects(static::exactly(6))
            ->method('convert')
            ->willReturnCallback(static::validatePayloadForJobCountAndPks([$batchSize, $batchSize, $batchSize, $batchSize, $batchSize, 2], $pks));

        $emitter = new ReceiveJobDispatchingEmitter($entityType, $jobConverter, $jobDispatcher, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls($entities, []);

        \iterable_to_array($emitter->emit($pks, $context, $stack));

        static::assertSame([], $pks);
    }

    public function testConvertsEntitiesToJobsAndDispatchesThemUntilAnExceptionIsThrown(): void
    {
        $entityType = FooBarEntity::class();
        $jobConverter = $this->createMock(EmittedEntitiesToJobsConverterInterface::class);
        $jobDispatcher = $this->createMock(JobDispatcherContract::class);
        $stack = $this->createMock(EmitterStackInterface::class);
        $context = $this->createMock(EmitContextInterface::class);
        $batchSize = 3;
        $pks = $this->generatePrimaryKeys($batchSize + 2);
        $entities = $this->generateEntities($pks);

        $jobConverter
            ->expects(static::exactly(2))
            ->method('convert')
            ->willReturnCallback(static::validatePayloadForJobCountAndPks([$batchSize, 2], $pks));
        $jobDispatcher
            ->expects(static::exactly(2))
            ->method('dispatch');

        $emitter = new ReceiveJobDispatchingEmitter($entityType, $jobConverter, $jobDispatcher, $batchSize);

        $stack->method('next')->willReturnCallback(static function () use ($entities): iterable {
            yield from $entities;

            throw new \RuntimeException('Test message');
        });

        static::expectException(\RuntimeException::class);
        static::expectExceptionMessage('Test message');

        \iterable_to_array($emitter->emit($pks, $context, $stack));

        static::assertSame([], $pks);
    }

    private function generatePrimaryKeys(int $count): array
    {
        return \array_map('strval', \range(100, 100 + ($count - 1)));
    }

    /**
     * @param string[] $primaryKeys
     *
     * @return FooBarEntity[]
     */
    private function generateEntities(array $primaryKeys): array
    {
        $result = [];

        foreach ($primaryKeys as $primaryKey) {
            $entity = new FooBarEntity();
            $entity->setPrimaryKey($primaryKey);

            $result[] = $entity;
        }

        return $result;
    }

    /**
     * @param int[]    $batchSizes
     * @param string[] $primaryKeys
     */
    private function validatePayloadForJobCountAndPks(array $batchSizes, array &$primaryKeys): \Closure
    {
        return static function (
            PortalNodeKeyInterface $portalNodeKey,
            DatasetEntityCollection $entities
        ) use (&$primaryKeys, &$batchSizes): JobCollection {
            $batchSize = \array_shift($batchSizes);

            static::assertIsInt($batchSize);
            static::assertCount($batchSize, $entities);

            foreach ($entities as $entity) {
                $pk = $entity->getPrimaryKey();

                static::assertNotNull($pk);
                static::assertContains($pk, $primaryKeys);

                $primaryKeys = \array_diff($primaryKeys, [$pk]);
            }

            return new JobCollection();
        };
    }
}
