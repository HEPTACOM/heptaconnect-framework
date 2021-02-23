<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Exploration;

use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract
 */
class ContractTest extends TestCase
{
    public function testExtendingExplorerContract(): void
    {
        $explorer = new class() extends ExplorerContract {
            public function explore(ExploreContextInterface $context, ExplorerStackInterface $stack): iterable
            {
                yield from [];
            }

            public function supports(): string
            {
                return FirstEntity::class;
            }
        };
        static::assertEquals(FirstEntity::class, $explorer->supports());
        static::assertCount(0, $explorer->explore(
            $this->createMock(ExploreContextInterface::class),
            $this->createMock(ExplorerStackInterface::class)
        ));
    }
}
