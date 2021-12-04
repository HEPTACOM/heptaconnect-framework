<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Reception;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverStack;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverStack
 */
class ReceiverStackTest extends TestCase
{
    public function testEmptyStackDoesNotFail(): void
    {
        $stack = new ReceiverStack([]);
        static::assertCount(0, $stack->next(
            new TypedDatasetEntityCollection(DatasetEntityContract::class),
            $this->createMock(ReceiveContextInterface::class)
        ));
    }

    public function testStackCallsEveryone(): void
    {
        $result1 = $this->createMock(DatasetEntityContract::class);
        $result2 = $this->createMock(DatasetEntityContract::class);
        $result3 = $this->createMock(DatasetEntityContract::class);

        $receiver1 = $this->createMock(ReceiverContract::class);
        $receiver1->expects(static::once())
            ->method('receive')
            ->willReturnCallback(fn ($col, $con, $stack) => $stack->next($col, $con));

        $receiver2 = $this->createMock(ReceiverContract::class);
        $receiver2->expects(static::once())
            ->method('receive')
            ->willReturnCallback(fn ($col, $con, $stack) => $stack->next($col, $con))
        ;

        $receiver3 = $this->createMock(ReceiverContract::class);
        $receiver3->expects(static::once())
            ->method('receive')
            ->willReturnCallback(static function (
                TypedDatasetEntityCollection $col, ReceiveContextInterface $con, ReceiverStackInterface $stack
            ) use ($result3, $result2, $result1): iterable {
                yield $result1;
                yield $result2;
                yield $result3;
                yield from $stack->next($col, $con);
            });

        $stack = new ReceiverStack([$receiver1, $receiver2, $receiver3]);
        static::assertCount(3, $stack->next(new TypedDatasetEntityCollection(DatasetEntityContract::class), $this->createMock(ReceiveContextInterface::class)));
    }
}
