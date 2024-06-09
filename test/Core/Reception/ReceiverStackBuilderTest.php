<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Reception;

use Heptacom\HeptaConnect\Core\Reception\ReceiverStack;
use Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilder;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(ReceiverStack::class)]
#[CoversClass(ReceiverStackBuilder::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(TypedDatasetEntityCollection::class)]
#[CoversClass(ReceiverContract::class)]
#[CoversClass(ReceiverCollection::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
final class ReceiverStackBuilderTest extends TestCase
{
    public function testStackBuilderManualOrder(): void
    {
        $stackBuilder = new ReceiverStackBuilder(
            new ReceiverCollection(),
            FooBarEntity::class(),
            $this->createMock(LoggerInterface::class),
        );

        $calc = [];

        $receiver1 = $this->createMock(ReceiverContract::class);
        $receiver1->method('receive')
            ->willReturnCallback(
                static function (TypedDatasetEntityCollection $e, ReceiveContextInterface $c, ReceiverStackInterface $s) use (&$calc): iterable {
                    $calc[] = 1;

                    return $s->next($e, $c);
                }
            );
        $receiver1->method('supports')->willReturn(FooBarEntity::class);
        $receiver2 = $this->createMock(ReceiverContract::class);
        $receiver2->method('receive')
            ->willReturnCallback(
                static function (TypedDatasetEntityCollection $e, ReceiveContextInterface $c, ReceiverStackInterface $s) use (&$calc): iterable {
                    $calc[] = 2;

                    return $s->next($e, $c);
                }
            );
        $receiver2->method('supports')->willReturn(FooBarEntity::class);
        $stackBuilder->push($receiver1); // resembles source
        $stackBuilder->push($receiver2); // resembles decorators
        $stack = $stackBuilder->build();
        $stack->next(
            new TypedDatasetEntityCollection(FooBarEntity::class()),
            $this->createMock(ReceiveContextInterface::class)
        );

        static::assertEquals([2, 1], $calc);
    }

    public function testStackBuilderOrderFromCtor(): void
    {
        $calc = [];

        $receiver1 = $this->createMock(ReceiverContract::class);
        $receiver1->method('receive')
            ->willReturnCallback(
                static function (TypedDatasetEntityCollection $e, ReceiveContextInterface $c, ReceiverStackInterface $s) use (&$calc): iterable {
                    $calc[] = 1;

                    return $s->next($e, $c);
                }
            );
        $receiver1->method('supports')->willReturn(FooBarEntity::class);
        $receiver2 = $this->createMock(ReceiverContract::class);
        $receiver2->method('receive')
            ->willReturnCallback(
                static function (TypedDatasetEntityCollection $e, ReceiveContextInterface $c, ReceiverStackInterface $s) use (&$calc): iterable {
                    $calc[] = 2;

                    return $s->next($e, $c);
                }
            );
        $receiver2->method('supports')->willReturn(FooBarEntity::class);

        $stackBuilder = new ReceiverStackBuilder(
            new ReceiverCollection([$receiver1, $receiver2, $receiver2]),
            FooBarEntity::class(),
            $this->createMock(LoggerInterface::class),
        );

        $stackBuilder->pushSource();
        $stackBuilder->pushDecorators();
        $stack = $stackBuilder->build();
        $stack->next(
            new TypedDatasetEntityCollection(FooBarEntity::class()),
            $this->createMock(ReceiveContextInterface::class)
        );

        static::assertEquals([2, 1], $calc);
    }

    public function testEmptyStack(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $stackBuilder = new ReceiverStackBuilder(new ReceiverCollection(), FooBarEntity::class(), $logger);
        $stackBuilder->pushSource();
        $stackBuilder->pushDecorators();
        $stack = $stackBuilder->build();

        $logger->expects(static::never())->method('debug');

        $stack->next(
            new TypedDatasetEntityCollection(FooBarEntity::class()),
            $this->createMock(ReceiveContextInterface::class)
        );
    }
}
