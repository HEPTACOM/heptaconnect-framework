<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Emission;

use Heptacom\HeptaConnect\Core\Emission\IdentityMappingEmitter;
use Heptacom\HeptaConnect\Core\Storage\PrimaryKeyToEntityHydrator;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Emission\AbstractBufferedResultProcessingEmitter
 * @covers \Heptacom\HeptaConnect\Core\Emission\IdentityMappingEmitter
 * @covers \Heptacom\HeptaConnect\Core\Storage\PrimaryKeyToEntityHydrator
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
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapPayload
 * @covers \Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapResult
 */
final class IdentityMappingEmitterTest extends TestCase
{
    public function testMapsPksInBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $stack = $this->createMock(EmitterStackInterface::class);
        $context = $this->createMock(EmitContextInterface::class);
        $identityMapAction = $this->createMock(IdentityMapActionInterface::class);
        $batchSize = 3;
        $entity = new FooBarEntity();
        $entity->setPrimaryKey('');

        $identityMapAction
            ->expects(static::exactly(5))
            ->method('map')
            ->willReturn(new IdentityMapResult(new MappedDatasetEntityCollection()))
            ->with(static::callback($this->validateMapPayloadCount($batchSize)));

        $emitter = new IdentityMappingEmitter($entityType, new PrimaryKeyToEntityHydrator(), $identityMapAction, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls(
            \array_fill(0, $batchSize * 5, $entity),
            []
        );

        \iterable_to_array($emitter->emit([], $context, $stack));
    }

    public function testConvertsRemainingPksIfPkCountIsNotDividablePerfectlyByBatchSize(): void
    {
        $entityType = FooBarEntity::class();
        $identityMapAction = $this->createMock(IdentityMapActionInterface::class);
        $container = $this->createMock(ContainerInterface::class);
        $stack = $this->createMock(EmitterStackInterface::class);
        $context = $this->createMock(EmitContextInterface::class);
        $batchSize = 3;
        $entity = new FooBarEntity();
        $entity->setPrimaryKey('');

        $container->method('get')->willReturnCallback(fn (string $id) => [
            LoggerInterface::class => $this->createMock(LoggerInterface::class),
        ][$id] ?? null);
        $context->method('getContainer')->willReturn($container);
        $identityMapAction
            ->expects(static::exactly(6))
            ->method('map')
            ->willReturn(new IdentityMapResult(new MappedDatasetEntityCollection()))
            ->withConsecutive([
                static::callback($this->validateMapPayloadCount($batchSize)),
            ], [
                static::callback($this->validateMapPayloadCount($batchSize)),
            ], [
                static::callback($this->validateMapPayloadCount($batchSize)),
            ], [
                static::callback($this->validateMapPayloadCount($batchSize)),
            ], [
                static::callback($this->validateMapPayloadCount($batchSize)),
            ], [
                static::callback($this->validateMapPayloadCount(2)),
            ]);

        $emitter = new IdentityMappingEmitter($entityType, new PrimaryKeyToEntityHydrator(), $identityMapAction, $batchSize);

        $stack->method('next')->willReturnOnConsecutiveCalls(
            \array_fill(0, $batchSize * 5 + 2, $entity),
            []
        );

        \iterable_to_array($emitter->emit([], $context, $stack));
    }

    public function testConvertsPksToJobsAndDispatchesThemUntilAnExceptionIsThrown(): void
    {
        $entityType = FooBarEntity::class();
        $container = $this->createMock(ContainerInterface::class);
        $identityMapAction = $this->createMock(IdentityMapActionInterface::class);
        $stack = $this->createMock(EmitterStackInterface::class);
        $context = $this->createMock(EmitContextInterface::class);
        $batchSize = 3;
        $entity = new FooBarEntity();
        $entity->setPrimaryKey('');

        $container->method('get')->willReturnCallback(fn (string $id) => [
            LoggerInterface::class => $this->createMock(LoggerInterface::class),
        ][$id] ?? null);
        $context->method('getContainer')->willReturn($container);
        $identityMapAction
            ->expects(static::exactly(2))
            ->method('map')
            ->willReturn(new IdentityMapResult(new MappedDatasetEntityCollection()))
            ->withConsecutive([
                static::callback($this->validateMapPayloadCount($batchSize)),
            ], [
                static::callback($this->validateMapPayloadCount(2)),
            ]);

        $emitter = new IdentityMappingEmitter($entityType, new PrimaryKeyToEntityHydrator(), $identityMapAction, $batchSize);

        $stack->method('next')->willReturnCallback(static function () use ($entity, $batchSize): iterable {
            yield from \array_fill(0, $batchSize + 2, $entity);

            throw new \RuntimeException('Test message');
        });

        static::expectExceptionMessage('Test message');

        \iterable_to_array($emitter->emit([], $context, $stack));
    }

    private function validateMapPayloadCount(int $batchSize): \Closure
    {
        return static function (IdentityMapPayload $payload) use ($batchSize): bool {
            static::assertCount($batchSize, $payload->getEntityCollection());

            return true;
        };
    }
}
