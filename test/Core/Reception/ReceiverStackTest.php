<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Reception;

use Heptacom\HeptaConnect\Core\Reception\ReceiverStack;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentCollection;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(ReceiverStack::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(DatasetEntityCollection::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(TypedDatasetEntityCollection::class)]
#[CoversClass(ReceiverCollection::class)]
#[CoversClass(AttachmentCollection::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
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

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects(static::exactly(3))
            ->method('debug')
            ->willReturnCallback(static function (mixed $message, array $context): void {
                static::assertArrayHasKey('receiver', $context);
            });

        $stack = new ReceiverStack([$receiver1, $receiver2, $receiver3], $logger);
        $stackResult = \iterable_to_array($stack->next(new TypedDatasetEntityCollection(FooBarEntity::class()), $this->createMock(ReceiveContextInterface::class)));
        static::assertCount(3, $stackResult);
    }
}
