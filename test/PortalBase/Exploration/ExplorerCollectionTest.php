<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Exploration;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\SecondEntity;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(ExplorerContract::class)]
#[CoversClass(ExplorerCollection::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
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
        static::assertNotEmpty($collection->bySupport(FirstEntity::class()));
        static::assertNotEmpty($collection->bySupport(SecondEntity::class()));
        static::assertCount(3, $collection->bySupport(FirstEntity::class()));
        static::assertCount(2, $collection->bySupport(SecondEntity::class()));
    }

    private function getExplorer(string $support): ExplorerContract
    {
        $explorer = $this->createMock(ExplorerContract::class);
        $explorer->expects(static::any())->method('supports')->willReturn($support);

        return $explorer;
    }
}
