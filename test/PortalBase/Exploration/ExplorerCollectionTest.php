<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Exploration;

use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\SecondEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection
 */
final class ExplorerCollectionTest extends TestCase
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
        static::assertNotEmpty(\iterable_to_array($collection->bySupport(FirstEntity::class)));
        static::assertNotEmpty(\iterable_to_array($collection->bySupport(SecondEntity::class)));
        static::assertCount(3, \iterable_to_array($collection->bySupport(FirstEntity::class)));
        static::assertCount(2, \iterable_to_array($collection->bySupport(SecondEntity::class)));
    }

    private function getExplorer(string $support): ExplorerContract
    {
        $explorer = $this->createMock(ExplorerContract::class);
        $explorer->expects(static::any())->method('supports')->willReturn($support);

        return $explorer;
    }
}
