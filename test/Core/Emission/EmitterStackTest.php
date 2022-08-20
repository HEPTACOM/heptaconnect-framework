<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Emission;

use Heptacom\HeptaConnect\Core\Emission\EmitterStack;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Core\Emission\EmitterStack
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct
 */
final class EmitterStackTest extends TestCase
{
    public function testEmptyStackDoesNotFail(): void
    {
        $stack = new EmitterStack([], FirstEntity::class(), $this->createMock(LoggerInterface::class));
        static::assertTrue(FirstEntity::class()->equals($stack->supports()));
        static::assertCount(0, $stack->next(
            [],
            $this->createMock(EmitContextInterface::class)
        ));
    }

    public function testStackCallsEveryone(): void
    {
        $result1 = new MappedDatasetEntityStruct($this->createMock(MappingInterface::class), new FirstEntity());
        $result2 = new MappedDatasetEntityStruct($this->createMock(MappingInterface::class), new FirstEntity());
        $result3 = new MappedDatasetEntityStruct($this->createMock(MappingInterface::class), new FirstEntity());

        $emitter1 = $this->createMock(EmitterContract::class);
        $emitter1->expects(static::once())
            ->method('emit')
            ->willReturnCallback(fn ($col, $con, $stack) => $stack->next($col, $con));

        $emitter2 = $this->createMock(EmitterContract::class);
        $emitter2->expects(static::once())
            ->method('emit')
            ->willReturnCallback(fn ($col, $con, $stack) => $stack->next($col, $con));

        $emitter3 = $this->createMock(EmitterContract::class);
        $emitter3->expects(static::once())
            ->method('emit')
            ->willReturnCallback(static function (
                iterable $ids,
                EmitContextInterface $con,
                EmitterStackInterface $stack
            ) use ($result3, $result2, $result1): iterable {
                yield $result1;
                yield $result2;
                yield $result3;
                yield from $stack->next($ids, $con);
            });

        $stack = new EmitterStack([$emitter1, $emitter2, $emitter3], FirstEntity::class(), $this->createMock(LoggerInterface::class));
        static::assertCount(3, $stack->next([], $this->createMock(EmitContextInterface::class)));
    }
}
