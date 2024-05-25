<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Exploration;

use Heptacom\HeptaConnect\Core\Exploration\ExplorerStack;
use Heptacom\HeptaConnect\Core\Exploration\ExplorerStackBuilder;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(ExplorerStack::class)]
#[CoversClass(ExplorerStackBuilder::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(ExplorerContract::class)]
#[CoversClass(ExplorerCollection::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
final class ExplorerStackBuilderTest extends TestCase
{
    public function testStackBuilderManualOrder(): void
    {
        $stackBuilder = new ExplorerStackBuilder(
            new ExplorerCollection(),
            FooBarEntity::class(),
            $this->createMock(LoggerInterface::class),
        );

        $calc = [];

        $explorer1 = $this->createMock(ExplorerContract::class);
        $explorer1->method('explore')
            ->willReturnCallback(
                static function (ExploreContextInterface $c, ExplorerStackInterface $s) use (&$calc): iterable {
                    $calc[] = 1;

                    return $s->next($c);
                }
            );
        $explorer1->method('supports')->willReturn(FooBarEntity::class);
        $explorer2 = $this->createMock(ExplorerContract::class);
        $explorer2->method('explore')
            ->willReturnCallback(
                static function (ExploreContextInterface $c, ExplorerStackInterface $s) use (&$calc): iterable {
                    $calc[] = 2;

                    return $s->next($c);
                }
            );
        $explorer2->method('supports')->willReturn(FooBarEntity::class);
        $stackBuilder->push($explorer1); // resembles source
        $stackBuilder->push($explorer2); // resembles decorators
        $stack = $stackBuilder->build();
        $stack->next($this->createMock(ExploreContextInterface::class));

        static::assertEquals([2, 1], $calc);
    }

    public function testStackBuilderOrderFromCtor(): void
    {
        $calc = [];

        $explorer1 = $this->createMock(ExplorerContract::class);
        $explorer1->method('explore')
            ->willReturnCallback(
                static function (ExploreContextInterface $c, ExplorerStackInterface $s) use (&$calc): iterable {
                    $calc[] = 1;

                    return $s->next($c);
                }
            );
        $explorer1->method('supports')->willReturn(FooBarEntity::class);
        $explorer2 = $this->createMock(ExplorerContract::class);
        $explorer2->method('explore')
            ->willReturnCallback(
                static function (ExploreContextInterface $c, ExplorerStackInterface $s) use (&$calc): iterable {
                    $calc[] = 2;

                    return $s->next($c);
                }
            );
        $explorer2->method('supports')->willReturn(FooBarEntity::class);

        $stackBuilder = new ExplorerStackBuilder(
            new ExplorerCollection([$explorer1, $explorer2, $explorer2]),
            FooBarEntity::class(),
            $this->createMock(LoggerInterface::class),
        );

        $stackBuilder->pushSource();
        $stackBuilder->pushDecorators();
        $stack = $stackBuilder->build();
        $stack->next($this->createMock(ExploreContextInterface::class));

        static::assertEquals([2, 1], $calc);
    }
}
