<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Reception;

use Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilder;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Reception\ReceiverStackBuilder
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\EntityTypeClassString
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverStack
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection
 */
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
}
