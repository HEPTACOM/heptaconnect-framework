<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test;

use Heptacom\HeptaConnect\Core\Component\LogMessage;
use Heptacom\HeptaConnect\Core\Emission\Contract\EmissionActorInterface;
use Heptacom\HeptaConnect\Core\Emission\Contract\EmitContextFactoryInterface;
use Heptacom\HeptaConnect\Core\Emission\Contract\EmitterStackBuilderFactoryInterface;
use Heptacom\HeptaConnect\Core\Emission\Contract\EmitterStackBuilderInterface;
use Heptacom\HeptaConnect\Core\Emission\EmitService;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterStack;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;
use Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappingComponentCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Emission\EmitService
 * @covers \Heptacom\HeptaConnect\Core\Component\LogMessage
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityTypeClassString
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterStack
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappingComponentCollection
 */
final class EmitServiceTest extends TestCase
{
    /**
     * @dataProvider provideEmitCount
     */
    public function testEmitCount(int $count): void
    {
        $mapping = $this->createMock(MappingComponentStructContract::class);
        $mapping->expects(static::exactly($count))
            ->method('getEntityType')
            ->willReturn(FooBarEntity::class());

        $stack = new EmitterStack([], FooBarEntity::class());
        $stackBuilder = $this->createMock(EmitterStackBuilderInterface::class);
        $stackBuilder->method('build')->willReturn($stack);
        $stackBuilder->method('pushSource')->willReturnSelf();
        $stackBuilder->method('pushDecorators')->willReturnSelf();
        $stackBuilder->method('isEmpty')->willReturn(true);
        $stackBuilderFactory = $this->createMock(EmitterStackBuilderFactoryInterface::class);
        $stackBuilderFactory->method('createEmitterStackBuilder')->willReturn($stackBuilder);

        $emitService = new EmitService(
            $this->createMock(EmitContextFactoryInterface::class),
            $this->createMock(LoggerInterface::class),
            $this->createMock(StorageKeyGeneratorContract::class),
            $stackBuilderFactory,
            $this->createMock(EmissionActorInterface::class),
        );
        $emitService->emit(new TypedMappingComponentCollection(FooBarEntity::class(), \array_fill(0, $count, $mapping)));
    }

    /**
     * @dataProvider provideEmitCount
     */
    public function testMissingEmitter(int $count): void
    {
        $emitContext = $this->createMock(EmitContextInterface::class);

        $emitContextFactory = $this->createMock(EmitContextFactoryInterface::class);
        $emitContextFactory->method('createContext')->willReturn($emitContext);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($count > 0 ? static::atLeastOnce() : static::never())
            ->method('critical')
            ->with(LogMessage::EMIT_NO_EMITTER_FOR_TYPE());

        $mapping = $this->createMock(MappingComponentStructContract::class);
        $mapping->expects(static::atLeast($count))
            ->method('getEntityType')
            ->willReturn(FooBarEntity::class());

        $stack = new EmitterStack([], FooBarEntity::class());
        $stackBuilder = $this->createMock(EmitterStackBuilderInterface::class);
        $stackBuilder->method('build')->willReturn($stack);
        $stackBuilder->method('pushSource')->willReturnSelf();
        $stackBuilder->method('pushDecorators')->willReturnSelf();
        $stackBuilder->method('isEmpty')->willReturn(true);
        $stackBuilderFactory = $this->createMock(EmitterStackBuilderFactoryInterface::class);
        $stackBuilderFactory->method('createEmitterStackBuilder')->willReturn($stackBuilder);

        $emitService = new EmitService(
            $emitContextFactory,
            $logger,
            $this->createMock(StorageKeyGeneratorContract::class),
            $stackBuilderFactory,
            $this->createMock(EmissionActorInterface::class),
        );
        $emitService->emit(new TypedMappingComponentCollection(FooBarEntity::class(), \array_fill(0, $count, $mapping)));
    }

    /**
     * @return iterable<array-key, array<array-key, int>>
     */
    public function provideEmitCount(): iterable
    {
        yield [0];
        yield [1];
        yield [2];
        yield [3];
        yield [4];
        yield [5];
        yield [6];
        yield [7];
        yield [8];
        yield [9];
        yield [10];
    }
}
