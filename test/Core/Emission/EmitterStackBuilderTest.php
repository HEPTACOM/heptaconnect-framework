<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Emission;

use Heptacom\HeptaConnect\Core\Emission\EmitterStack;
use Heptacom\HeptaConnect\Core\Emission\EmitterStackBuilder;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(EmitterStack::class)]
#[CoversClass(EmitterStackBuilder::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(EmitterContract::class)]
#[CoversClass(EmitterCollection::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
final class EmitterStackBuilderTest extends TestCase
{
    public function testStackBuilderManualOrder(): void
    {
        $stackBuilder = new EmitterStackBuilder(
            new EmitterCollection(),
            FooBarEntity::class(),
            $this->createMock(LoggerInterface::class),
        );

        $calc = [];

        $emitter1 = $this->createMock(EmitterContract::class);
        $emitter1->method('emit')
            ->willReturnCallback(
                static function (iterable $ids, EmitContextInterface $c, EmitterStackInterface $s) use (&$calc): iterable {
                    $calc[] = 1;

                    return $s->next($ids, $c);
                }
            );
        $emitter1->method('supports')->willReturn(FooBarEntity::class);

        $emitter2 = $this->createMock(EmitterContract::class);
        $emitter2->method('emit')
            ->willReturnCallback(
                static function (iterable $ids, EmitContextInterface $c, EmitterStackInterface $s) use (&$calc): iterable {
                    $calc[] = 2;

                    return $s->next($ids, $c);
                }
            );
        $emitter2->method('supports')->willReturn(FooBarEntity::class);
        $stackBuilder->push($emitter1); // resembles source
        $stackBuilder->push($emitter2); // resembles decorators
        $stack = $stackBuilder->build();
        $stack->next([], $this->createMock(EmitContextInterface::class));

        static::assertEquals([2, 1], $calc);
    }

    public function testStackBuilderOrderFromCtor(): void
    {
        $calc = [];

        $emitter1 = $this->createMock(EmitterContract::class);
        $emitter1->method('emit')
            ->willReturnCallback(
                static function (iterable $ids, EmitContextInterface $c, EmitterStackInterface $s) use (&$calc): iterable {
                    $calc[] = 1;

                    return $s->next($ids, $c);
                }
            );
        $emitter1->method('supports')->willReturn(FooBarEntity::class);

        $emitter2 = $this->createMock(EmitterContract::class);
        $emitter2->method('emit')
            ->willReturnCallback(
                static function (iterable $ids, EmitContextInterface $c, EmitterStackInterface $s) use (&$calc): iterable {
                    $calc[] = 2;

                    return $s->next($ids, $c);
                }
            );
        $emitter2->method('supports')->willReturn(FooBarEntity::class);

        $stackBuilder = new EmitterStackBuilder(
            new EmitterCollection([$emitter1, $emitter2, $emitter2]),
            FooBarEntity::class(),
            $this->createMock(LoggerInterface::class),
        );
        $stackBuilder->pushSource();
        $stackBuilder->pushDecorators();
        $stack = $stackBuilder->build();
        $stack->next([], $this->createMock(EmitContextInterface::class));

        static::assertEquals([2, 1], $calc);
    }
}
