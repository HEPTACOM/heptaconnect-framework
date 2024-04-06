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
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Exploration\AbstractBufferedResultProcessingExplorer
 * @covers \Heptacom\HeptaConnect\Core\Exploration\DirectEmitter
 * @covers \Heptacom\HeptaConnect\Core\Exploration\DirectEmittingExplorer
 * @covers \Heptacom\HeptaConnect\Core\Job\JobCollection
 * @covers \Heptacom\HeptaConnect\Core\Job\Type\AbstractJobType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Scalar\StringCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait
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
        $pks = $this->generatePrimaryKeys($batchSize * 5);
        $entities = $this->generateEntities($pks);

        $explorerStack
            ->expects(static::once())
            ->method('next')
            ->willReturn($entities);

        $emitterStackProcessor
            ->expects(static::exactly(5))
            ->method('processStack')
            ->willReturnCallback(static::validateMapPayloadCountAndPks([$batchSize, $batchSize, $batchSize, $batchSize, $batchSize], $pks, $entities));

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

        static::assertSame([], $pks);
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
        $batchSize = 3;
        $pks = $this->generatePrimaryKeys($batchSize * 5 + 2);
        $entities = $this->generateEntities($pks);

        $emitContext->method('getLogger')->willReturn($portalNodeLogger);
        $exploreContext->method('getLogger')->willReturn($portalNodeLogger);
        $explorerStack
            ->expects(static::once())
            ->method('next')
            ->willReturn($entities);

        $emitterStackProcessor
            ->expects(static::exactly(6))
            ->method('processStack')
            ->willReturnCallback(static::validateMapPayloadCountAndPks([$batchSize, $batchSize, $batchSize, $batchSize, $batchSize, 2], $pks, $entities));

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

        static::assertSame([], $pks);
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
        $logger = $this->createMock(LoggerInterface::class);
        $batchSize = 3;
        $pks = $this->generatePrimaryKeys($batchSize + 2);
        $entities = $this->generateEntities($pks);

        $emitContext->method('getLogger')->willReturn($portalNodeLogger);
        $exploreContext->method('getLogger')->willReturn($portalNodeLogger);
        $explorerStack
            ->expects(static::once())
            ->method('next')
            ->willReturnCallback(static function () use ($entities): iterable {
                yield from $entities;

                throw new \RuntimeException('Test message');
            });

        $emitterStackProcessor
            ->expects(static::exactly(2))
            ->method('processStack')
            ->willReturnCallback(static::validateMapPayloadCountAndPks([$batchSize, 2], $pks, $entities));

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
     * @param int[]          $batchSizes
     * @param string[]       $primaryKeys
     * @param FooBarEntity[] $entities
     */
    private function validateMapPayloadCountAndPks(array $batchSizes, array &$primaryKeys, array $entities): \Closure
    {
        return static function (
            array $externalIds,
            EmitterStackInterface $stack,
            EmitContextInterface $context
        ) use (&$primaryKeys, &$batchSizes, $entities): TypedDatasetEntityCollection {
            $batchSize = \array_shift($batchSizes);

            static::assertIsInt($batchSize);
            static::assertCount($batchSize, $externalIds);

            foreach ($externalIds as $externalId) {
                static::assertNotNull($externalId);
                static::assertContains($externalId, $primaryKeys);

                $primaryKeys = \array_diff($primaryKeys, [$externalId]);
            }

            return new TypedDatasetEntityCollection(FooBarEntity::class(), \array_filter(
                $entities,
                static fn (FooBarEntity $entity): bool => \in_array($entity->getPrimaryKey(), $externalIds, true)
            ));
        };
    }
}
