<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\ExplorerInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\ExplorerStackInterface;
use Heptacom\HeptaConnect\Portal\Base\ExplorerStack;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\ExplorerStack
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
        $result1 = $this->createMock(DatasetEntityInterface::class);
        $result2 = $this->createMock(DatasetEntityInterface::class);
        $result3 = $this->createMock(DatasetEntityInterface::class);

        $explorer1 = $this->createMock(ExplorerInterface::class);
        $explorer1->expects(static::once())
            ->method('explore')
            ->id('explorer1Explore')
            ->willReturnCallback(static function (ExploreContextInterface $c, ExplorerStackInterface $stack): iterable {
                return $stack->next($c);
            });

        $explorer2 = $this->createMock(ExplorerInterface::class);
        $explorer2->expects(static::once())
            ->method('explore')
            ->after('explorer1Explore')
            ->id('explorer2Explore')
            ->willReturnCallback(static function (ExploreContextInterface $c, ExplorerStackInterface $stack): iterable {
                return $stack->next($c);
            })
        ;

        $explorer3 = $this->createMock(ExplorerInterface::class);
        $explorer3->expects(static::once())
            ->method('explore')
            ->after('explorer2Explore')
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
