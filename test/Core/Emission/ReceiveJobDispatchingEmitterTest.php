<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Emission;

use Heptacom\HeptaConnect\Core\Emission\ReceiveJobDispatchingEmitter;
use Heptacom\HeptaConnect\Core\Job\Contract\JobDispatcherContract;
use Heptacom\HeptaConnect\Core\Job\Transition\Contract\EmittedEntitiesToJobsConverterInterface;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use PHPUnit\Framework\Constraint\Count;
use PHPUnit\Framework\Constraint\IsNull;
use PHPUnit\Framework\Constraint\LogicalNot;
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
        $entity = new FooBarEntity();
        $entity->setPrimaryKey('');

        $jobConverter
            ->expects(static::exactly(5))
            ->method('convert')
            ->with(new LogicalNot(new IsNull()), new Count($batchSize));

        $emitter = new ReceiveJobDispatchingEmitter($entityType, $jobConverter, $jobDispatcher, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls(\array_fill(0, $batchSize * 5, $entity), []);

        \iterable_to_array($emitter->emit(\array_fill(0, $batchSize * 5, ''), $context, $stack));
    }

    public function testConvertsRemainingEntitiesIfEntityCountIsNotDividablePerfectlyByBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $jobConverter = $this->createMock(EmittedEntitiesToJobsConverterInterface::class);
        $jobDispatcher = $this->createMock(JobDispatcherContract::class);
        $stack = $this->createMock(EmitterStackInterface::class);
        $context = $this->createMock(EmitContextInterface::class);
        $batchSize = 3;
        $entity = new FooBarEntity();
        $entity->setPrimaryKey('');

        $jobConverter
            ->expects(static::exactly(6))
            ->method('convert')
            ->withConsecutive([
                new LogicalNot(new IsNull()), new Count($batchSize),
            ], [
                new LogicalNot(new IsNull()), new Count($batchSize),
            ], [
                new LogicalNot(new IsNull()), new Count($batchSize),
            ], [
                new LogicalNot(new IsNull()), new Count($batchSize),
            ], [
                new LogicalNot(new IsNull()), new Count($batchSize),
            ], [
                new LogicalNot(new IsNull()), new Count(2),
            ]);

        $emitter = new ReceiveJobDispatchingEmitter($entityType, $jobConverter, $jobDispatcher, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls(\array_fill(0, $batchSize * 5 + 2, $entity), []);

        \iterable_to_array($emitter->emit(\array_fill(0, $batchSize * 5 + 2, ''), $context, $stack));
    }

    public function testConvertsEntitiesToJobsAndDispatchesThemUntilAnExceptionIsThrown(): void
    {
        $entityType = FooBarEntity::class();
        $jobConverter = $this->createMock(EmittedEntitiesToJobsConverterInterface::class);
        $jobDispatcher = $this->createMock(JobDispatcherContract::class);
        $stack = $this->createMock(EmitterStackInterface::class);
        $context = $this->createMock(EmitContextInterface::class);
        $batchSize = 3;
        $entity = new FooBarEntity();
        $entity->setPrimaryKey('');

        $jobConverter
            ->expects(static::exactly(2))
            ->method('convert')
            ->withConsecutive([
                new LogicalNot(new IsNull()), new Count($batchSize),
            ], [
                new LogicalNot(new IsNull()), new Count(2),
            ]);
        $jobDispatcher
            ->expects(static::exactly(2))
            ->method('dispatch');

        $emitter = new ReceiveJobDispatchingEmitter($entityType, $jobConverter, $jobDispatcher, $batchSize);

        $stack->method('next')->willReturnCallback(static function () use ($batchSize, $entity): iterable {
            yield from \array_fill(0, $batchSize + 2, $entity);

            throw new \RuntimeException('Test message');
        });

        static::expectException(\RuntimeException::class);
        static::expectExceptionMessage('Test message');

        \iterable_to_array($emitter->emit(\array_fill(0, $batchSize * 17, ''), $context, $stack));
    }
}
