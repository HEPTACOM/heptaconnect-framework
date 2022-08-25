<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Exploration;

use Heptacom\HeptaConnect\Core\Emission\Contract\EmitterStackProcessorInterface;
use Heptacom\HeptaConnect\Core\Exploration\DirectEmitter;
use Heptacom\HeptaConnect\Core\Exploration\DirectEmittingExplorer;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Exploration\AbstractBufferedResultProcessingExplorer
 * @covers \Heptacom\HeptaConnect\Core\Exploration\DirectEmitter
 * @covers \Heptacom\HeptaConnect\Core\Exploration\DirectEmittingExplorer
 * @covers \Heptacom\HeptaConnect\Core\Job\JobCollection
 * @covers \Heptacom\HeptaConnect\Core\Job\Type\AbstractJobType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\DependencyTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct
 */
final class DirectEmittingExplorerTest extends TestCase
{
    public function testEmitEntitiesInBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $directEmitter = new DirectEmitter($entityType);
        $explorerStack = $this->createMock(ExplorerStackInterface::class);
        $exploreContext = $this->createMock(ExploreContextInterface::class);
        $emitterStack = $this->createMock(EmitterStackInterface::class);
        $emitterStackProcessor = $this->createMock(EmitterStackProcessorInterface::class);
        $emitContext = $this->createMock(EmitContextInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $batchSize = 3;

        $entity = new FooBarEntity();
        $entity->setPrimaryKey('');

        $explorerStack
            ->expects(static::once())
            ->method('next')
            ->willReturn(\array_fill(0, $batchSize * 5, $entity));

        $emitterStackProcessor
            ->expects(static::exactly(5))
            ->method('processStack')
            ->with(new IsIdentical(\array_fill(0, $batchSize, '')))
            ->willReturn(new TypedDatasetEntityCollection($entityType));

        $explorer = new DirectEmittingExplorer(
            $entityType,
            $directEmitter,
            $emitterStackProcessor,
            $emitterStack,
            $emitContext,
            $logger,
            $batchSize
        );

        \iterable_to_array($explorer->explore($exploreContext, $explorerStack));

        static::assertCount($batchSize, $directEmitter->getEntities());
    }

    public function testEmitRemainingEntitiesIfEntityCountIsNotDividablePerfectlyByBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $directEmitter = new DirectEmitter($entityType);
        $explorerStack = $this->createMock(ExplorerStackInterface::class);
        $exploreContext = $this->createMock(ExploreContextInterface::class);
        $emitterStack = $this->createMock(EmitterStackInterface::class);
        $emitterStackProcessor = $this->createMock(EmitterStackProcessorInterface::class);
        $emitContext = $this->createMock(EmitContextInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $portalNodeLogger = $this->createMock(LoggerInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $batchSize = 3;

        $entity = new FooBarEntity();
        $entity->setPrimaryKey('');

        $container->method('get')->willReturnCallback(static fn (string $id) => [
            LoggerInterface::class => $portalNodeLogger,
        ][$id] ?? null);
        $emitContext->method('getContainer')->willReturn($container);
        $exploreContext->method('getContainer')->willReturn($container);
        $explorerStack
            ->expects(static::once())
            ->method('next')
            ->willReturn(\array_fill(0, $batchSize * 5 + 2, $entity));

        $emitterStackProcessor
            ->expects(static::exactly(6))
            ->method('processStack')
            ->willReturn(new TypedDatasetEntityCollection($entityType));

        $explorer = new DirectEmittingExplorer(
            $entityType,
            $directEmitter,
            $emitterStackProcessor,
            $emitterStack,
            $emitContext,
            $logger,
            $batchSize
        );

        \iterable_to_array($explorer->explore($exploreContext, $explorerStack));

        static::assertCount(2, $directEmitter->getEntities());
    }

    public function testEmitEntitiesUntilAnExceptionIsThrown(): void
    {
        $entityType = FooBarEntity::class();
        $directEmitter = new DirectEmitter($entityType);
        $explorerStack = $this->createMock(ExplorerStackInterface::class);
        $exploreContext = $this->createMock(ExploreContextInterface::class);
        $emitterStack = $this->createMock(EmitterStackInterface::class);
        $emitterStackProcessor = $this->createMock(EmitterStackProcessorInterface::class);
        $emitContext = $this->createMock(EmitContextInterface::class);
        $portalNodeLogger = $this->createMock(LoggerInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $batchSize = 3;

        $entity = new FooBarEntity();
        $entity->setPrimaryKey('');

        $container->method('get')->willReturnCallback(static fn (string $id) => [
            LoggerInterface::class => $portalNodeLogger,
        ][$id] ?? null);
        $emitContext->method('getContainer')->willReturn($container);
        $exploreContext->method('getContainer')->willReturn($container);
        $explorerStack
            ->expects(static::once())
            ->method('next')
            ->willReturnCallback(static function () use ($batchSize, $entity): iterable {
                yield from \array_fill(0, $batchSize + 2, $entity);

                throw new \RuntimeException('Test message');
            });

        $emitterStackProcessor
            ->expects(static::exactly(2))
            ->method('processStack')
            ->willReturn(new TypedDatasetEntityCollection($entityType));

        $explorer = new DirectEmittingExplorer(
            $entityType,
            $directEmitter,
            $emitterStackProcessor,
            $emitterStack,
            $emitContext,
            $logger,
            $batchSize
        );

        \iterable_to_array($explorer->explore($exploreContext, $explorerStack));

        static::assertCount(2, $directEmitter->getEntities());
    }
}
