<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Exploration;

use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\SecondEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection
 */
class ExplorerCollectionTest extends TestCase
{
    public function testBySupport(): void
    {
        $collection = new ExplorerCollection();

        $collection->push([
            $this->getExplorer(FirstEntity::class),
            $this->getExplorer(SecondEntity::class),
            $this->getExplorer(FirstEntity::class),
            $this->getExplorer(SecondEntity::class),
            $this->getExplorer(FirstEntity::class),
        ]);
        static::assertNotEmpty($collection->bySupport(FirstEntity::class));
        static::assertNotEmpty($collection->bySupport(SecondEntity::class));
        static::assertCount(3, $collection->bySupport(FirstEntity::class));
        static::assertCount(2, $collection->bySupport(SecondEntity::class));
    }

    private function getExplorer(string $support): ExplorerContract
    {
        $explorer = $this->createMock(ExplorerContract::class);
        $explorer->expects(static::any())->method('supports')->willReturn($support);

        return $explorer;
    }
}
