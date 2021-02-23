<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Exploration;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerStack;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerStack
 */
class ExplorerStackTest extends TestCase
{
    public function testEmptyStackDoesNotFail(): void
    {
        $stack = new ExplorerStack([]);
        static::assertCount(0, $stack->next(
            $this->createMock(ExploreContextInterface::class)
        ));
    }

    public function testStackCallsEveryone(): void
    {
        $result1 = $this->createMock(DatasetEntityContract::class);
        $result2 = $this->createMock(DatasetEntityContract::class);
        $result3 = $this->createMock(DatasetEntityContract::class);

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
                ExploreContextInterface $c, ExplorerStackInterface $stack
            ) use ($result3, $result2, $result1): iterable {
                yield $result1;
                yield $result2;
                yield $result3;
                yield from $stack->next($c);
            });

        $stack = new ExplorerStack([$explorer1, $explorer2, $explorer3]);
        static::assertCount(3, $stack->next($this->createMock(ExploreContextInterface::class)));
    }
}
