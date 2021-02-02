<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Dependency;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Dependency
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DependencyCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\DependencyAwareTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\DependencyTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\PrimaryKeyTrait
 */
class DependencyTest extends TestCase
{
    public function testStructDependenciesAreEmptyByDefault(): void
    {
        $struct = new SerializationDatasetEntity();
        static::assertEquals(0, $struct->getDependencies()->count());
    }

    public function testStructAllowDuplicateValuesInDependencies(): void
    {
        $struct = new SerializationDatasetEntity();
        $struct->getDependencies()->push([
            new Dependency(SerializationDatasetEntity::class, '1'),
            new Dependency(SerializationDatasetEntity::class, '1'),
        ]);
        static::assertEquals(2, $struct->getDependencies()->count());
    }

    public function testStructAllowDifferentValuesInDependencies(): void
    {
        $struct = new SerializationDatasetEntity();
        $struct->getDependencies()->push([
            new Dependency(Dependency::class, '1'),
            new Dependency(SerializationDatasetEntity::class, '5'),
        ]);
        static::assertEquals(2, $struct->getDependencies()->count());
        /** @var Dependency $dep */
        $dep = $struct->getDependencies()->offsetGet(1);
        $dep->setDatasetEntityClass(SerializationDatasetEntity::class);

        /** @var Dependency $dep */
        $dep = $struct->getDependencies()->offsetGet(0);
        $dep->setDatasetEntityClass(Dependency::class);
        $dep->setPrimaryKey('17');

        /** @var Dependency $dep */
        $dep = $struct->getDependencies()->offsetGet(1);
        static::assertEquals(SerializationDatasetEntity::class, $dep->getDatasetEntityClass());
        static::assertEquals('5', $dep->getPrimaryKey());
    }
}
