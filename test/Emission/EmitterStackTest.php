<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Emission;

use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterStack;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterStack
 */
class EmitterStackTest extends TestCase
{
    public function testEmptyStackDoesNotFail(): void
    {
        $stack = new EmitterStack([]);
        static::assertCount(0, $stack->next(
            new MappingCollection(),
            $this->createMock(EmitContextInterface::class)
        ));
    }

    public function testStackCallsEveryone(): void
    {
        $result1 = $this->createMock(MappedDatasetEntityStruct::class);
        $result2 = $this->createMock(MappedDatasetEntityStruct::class);
        $result3 = $this->createMock(MappedDatasetEntityStruct::class);

        $emitter1 = $this->createMock(EmitterContract::class);
        $emitter1->expects(static::once())
            ->method('emit')
            ->willReturnCallback(static function (
                MappingCollection $col, EmitContextInterface $con, EmitterStackInterface $stack
            ): iterable {
                return $stack->next($col, $con);
            });

        $emitter2 = $this->createMock(EmitterContract::class);
        $emitter2->expects(static::once())
            ->method('emit')
            ->willReturnCallback(static function (
                MappingCollection $col, EmitContextInterface $con, EmitterStackInterface $stack
            ): iterable {
                return $stack->next($col, $con);
            })
        ;

        $emitter3 = $this->createMock(EmitterContract::class);
        $emitter3->expects(static::once())
            ->method('emit')
            ->willReturnCallback(static function (
                MappingCollection $col, EmitContextInterface $con, EmitterStackInterface $stack
            ) use ($result3, $result2, $result1): iterable {
                yield $result1;
                yield $result2;
                yield $result3;
                yield from $stack->next($col, $con);
            });

        $stack = new EmitterStack([$emitter1, $emitter2, $emitter3]);
        static::assertCount(3, $stack->next(new MappingCollection(), $this->createMock(EmitContextInterface::class)));
    }
}
