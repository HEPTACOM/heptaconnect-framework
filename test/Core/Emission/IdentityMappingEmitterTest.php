<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Emission;

use Heptacom\HeptaConnect\Core\Emission\AbstractBufferedResultProcessingEmitter;
use Heptacom\HeptaConnect\Core\Emission\IdentityMappingEmitter;
use Heptacom\HeptaConnect\Core\Storage\PrimaryKeyToEntityHydrator;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\StringCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(AbstractBufferedResultProcessingEmitter::class)]
#[CoversClass(IdentityMappingEmitter::class)]
#[CoversClass(PrimaryKeyToEntityHydrator::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(DatasetEntityCollection::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(TypedDatasetEntityCollection::class)]
#[CoversClass(EmitterContract::class)]
#[CoversClass(IdentityMapPayload::class)]
#[CoversClass(IdentityMapResult::class)]
#[CoversClass(AttachmentAwareTrait::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
#[CoversClass(StringCollection::class)]
final class IdentityMappingEmitterTest extends TestCase
{
    public function testMapsPksInBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $stack = $this->createMock(EmitterStackInterface::class);
        $context = $this->createMock(EmitContextInterface::class);
        $identityMapAction = $this->createMock(IdentityMapActionInterface::class);
        $batchSize = 3;
        $pks = $this->generatePrimaryKeys($batchSize * 5);
        $entities = $this->generateEntities($pks);

        $identityMapAction
            ->expects(static::exactly(5))
            ->method('map')
            ->willReturnCallback($this->validateMapPayloadCountAndPks(\array_fill(0, 5, $batchSize), $pks));

        $emitter = new IdentityMappingEmitter($entityType, new PrimaryKeyToEntityHydrator(), $identityMapAction, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls($entities, []);

        \iterable_to_array($emitter->emit($pks, $context, $stack));

        static::assertSame([], $pks);
    }

    public function testConvertsRemainingPksIfPkCountIsNotDividablePerfectlyByBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $identityMapAction = $this->createMock(IdentityMapActionInterface::class);
        $stack = $this->createMock(EmitterStackInterface::class);
        $context = $this->createMock(EmitContextInterface::class);
        $batchSize = 3;
        $pks = $this->generatePrimaryKeys($batchSize * 5 + 2);
        $entities = $this->generateEntities($pks);

        $context->method('getLogger')->willReturn($this->createMock(LoggerInterface::class));
        $identityMapAction
            ->expects(static::exactly(6))
            ->method('map')
            ->willReturnCallback($this->validateMapPayloadCountAndPks([$batchSize, $batchSize, $batchSize, $batchSize, $batchSize, 2], $pks));

        $emitter = new IdentityMappingEmitter($entityType, new PrimaryKeyToEntityHydrator(), $identityMapAction, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls($entities, []);

        \iterable_to_array($emitter->emit($pks, $context, $stack));

        static::assertSame([], $pks);
    }

    public function testConvertsPksToJobsAndDispatchesThemUntilAnExceptionIsThrown(): void
    {
        $entityType = FooBarEntity::class();
        $identityMapAction = $this->createMock(IdentityMapActionInterface::class);
        $stack = $this->createMock(EmitterStackInterface::class);
        $context = $this->createMock(EmitContextInterface::class);
        $batchSize = 3;
        $pks = $this->generatePrimaryKeys($batchSize + 2);
        $entities = $this->generateEntities($pks);

        $context->method('getLogger')->willReturn($this->createMock(LoggerInterface::class));
        $identityMapAction
            ->expects(static::exactly(2))
            ->method('map')
            ->willReturnCallback($this->validateMapPayloadCountAndPks([$batchSize, 2], $pks));

        $emitter = new IdentityMappingEmitter($entityType, new PrimaryKeyToEntityHydrator(), $identityMapAction, $batchSize);

        $stack->method('next')->willReturnCallback(static function () use ($entities): iterable {
            yield from $entities;

            throw new \RuntimeException('Test message');
        });

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
    private function validateMapPayloadCountAndPks(array $batchSizes, array &$primaryKeys): \Closure
    {
        return static function (IdentityMapPayload $payload) use (&$primaryKeys, &$batchSizes): IdentityMapResult {
            $batchSize = \array_shift($batchSizes);

            static::assertIsInt($batchSize);
            static::assertCount($batchSize, $payload->getEntityCollection());

            foreach ($payload->getEntityCollection() as $entity) {
                $pk = $entity->getPrimaryKey();

                static::assertNotNull($pk);
                static::assertContains($pk, $primaryKeys);

                $primaryKeys = \array_diff($primaryKeys, [$pk]);
            }

            return new IdentityMapResult(new MappedDatasetEntityCollection());
        };
    }
}
