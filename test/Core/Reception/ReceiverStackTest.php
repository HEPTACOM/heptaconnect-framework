<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Reception;

use Heptacom\HeptaConnect\Core\Reception\ReceiverStack;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Reception\ReceiverStack
 * @covers \Heptacom\HeptaConnect\Utility\Attachment\AttachmentCollection
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection
 */
final class ReceiverStackTest extends TestCase
{
    public function testEmptyStackDoesNotFail(): void
    {
        $stack = new ReceiverStack([], $this->createMock(LoggerInterface::class));
        static::assertCount(0, $stack->next(
            new TypedDatasetEntityCollection(FirstEntity::class()),
            $this->createMock(ReceiveContextInterface::class)
        ));
    }

    public function testStackCallsEveryone(): void
    {
        $result1 = new FooBarEntity();
        $result2 = new FooBarEntity();
        $result3 = new FooBarEntity();

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
                TypedDatasetEntityCollection $col,
                ReceiveContextInterface $con,
                ReceiverStackInterface $stack
            ) use ($result3, $result2, $result1): iterable {
                yield $result1;
                yield $result2;
                yield $result3;
                yield from $stack->next($col, $con);
            });

        $stack = new ReceiverStack([$receiver1, $receiver2, $receiver3], $this->createMock(LoggerInterface::class));
        $stackResult = \iterable_to_array($stack->next(new TypedDatasetEntityCollection(FooBarEntity::class()), $this->createMock(ReceiveContextInterface::class)));
        static::assertCount(3, $stackResult);
    }
}
