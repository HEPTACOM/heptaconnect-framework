<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Exploration;

use Heptacom\HeptaConnect\Core\Exploration\ExplorerStack;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Exploration\ExplorerStack
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection
 */
final class ExplorerStackTest extends TestCase
{
    public function testEmptyStackDoesNotFail(): void
    {
        $stack = new ExplorerStack([], FooBarEntity::class(), $this->createMock(LoggerInterface::class));
        static::assertCount(0, $stack->next(
            $this->createMock(ExploreContextInterface::class)
        ));
    }

    public function testStackCallsEveryone(): void
    {
        $result1 = new FooBarEntity();
        $result2 = new FooBarEntity();
        $result3 = new FooBarEntity();

        $explorer1 = $this->createMock(ExplorerContract::class);
        $explorer1->expects(static::once())
            ->method('explore')
            ->willReturnCallback(fn (ExploreContextInterface $c, ExplorerStackInterface $stack) => $stack->next($c));

        $explorer2 = $this->createMock(ExplorerContract::class);
        $explorer2->expects(static::once())
            ->method('explore')
            ->willReturnCallback(fn (ExploreContextInterface $c, ExplorerStackInterface $stack) => $stack->next($c));

        $explorer3 = $this->createMock(ExplorerContract::class);
        $explorer3->expects(static::once())
            ->method('explore')
            ->willReturnCallback(static function (
                ExploreContextInterface $c,
                ExplorerStackInterface $stack
            ) use ($result3, $result2, $result1): iterable {
                yield $result1;
                yield $result2;
                yield $result3;
                yield from $stack->next($c);
            });

        $stack = new ExplorerStack(
            [$explorer1, $explorer2, $explorer3],
            FooBarEntity::class(),
            $this->createMock(LoggerInterface::class)
        );
        $stackResult = \iterable_to_array($stack->next($this->createMock(ExploreContextInterface::class)));
        static::assertCount(3, $stackResult);
    }
}
