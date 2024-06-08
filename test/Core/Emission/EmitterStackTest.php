<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Emission;

use Heptacom\HeptaConnect\Core\Emission\EmitterStack;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(EmitterStack::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(EmitterCollection::class)]
#[CoversClass(MappedDatasetEntityStruct::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
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

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(static::exactly(3))
            ->method('debug')
            ->willReturnCallback(static function (mixed $message, array $context): void {
                static::assertArrayHasKey('emitter', $context);
            });

        $stack = new EmitterStack([$emitter1, $emitter2, $emitter3], FirstEntity::class(), $logger);
        $stackResult = \iterable_to_array($stack->next([], $this->createMock(EmitContextInterface::class)));
        static::assertCount(3, $stackResult);
    }
}
