<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\ReceiverStack;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\ReceiverStack
 */
class ReceiverStackTest extends TestCase
{
    public function testEmptyStackDoesNotFail(): void
    {
        $stack = new ReceiverStack([]);
        static::assertCount(0, $stack->next(
            new MappedDatasetEntityCollection(),
            $this->createMock(ReceiveContextInterface::class)
        ));
    }

    public function testStackCallsEveryone(): void
    {
        $result1 = $this->createMock(MappingInterface::class);
        $result2 = $this->createMock(MappingInterface::class);
        $result3 = $this->createMock(MappingInterface::class);

        $receiver1 = $this->createMock(ReceiverInterface::class);
        $receiver1->expects(static::once())
            ->method('receive')
            ->willReturnCallback(static function (
                MappedDatasetEntityCollection $col, ReceiveContextInterface $con, ReceiverStackInterface $stack
            ): iterable {
                return $stack->next($col, $con);
            });

        $receiver2 = $this->createMock(ReceiverInterface::class);
        $receiver2->expects(static::once())
            ->method('receive')
            ->willReturnCallback(static function (
                MappedDatasetEntityCollection $col, ReceiveContextInterface $con, ReceiverStackInterface $stack
            ): iterable {
                return $stack->next($col, $con);
            })
        ;

        $receiver3 = $this->createMock(ReceiverInterface::class);
        $receiver3->expects(static::once())
            ->method('receive')
            ->willReturnCallback(static function (
                MappedDatasetEntityCollection $col, ReceiveContextInterface $con, ReceiverStackInterface $stack
            ) use ($result3, $result2, $result1): iterable {
                yield $result1;
                yield $result2;
                yield $result3;
                yield from $stack->next($col, $con);
            });

        $stack = new ReceiverStack([$receiver1, $receiver2, $receiver3]);
        static::assertCount(3, $stack->next(new MappedDatasetEntityCollection(), $this->createMock(ReceiveContextInterface::class)));
    }
}
