<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\MappingCollection;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\DatasetEntityStruct;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\MappingStruct;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\SecondEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct
 * @covers \Heptacom\HeptaConnect\Storage\Base\MappingCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\TypedMappingCollection
 */
final class MappingCollectionTest extends TestCase
{
    public function testBuildingMappingCollection(): void
    {
        $collection = new MappingCollection();
        $collection->push([
            new MappingStruct(
                $this->createMock(PortalNodeKeyInterface::class),
                $this->createMock(MappingNodeKeyInterface::class),
                DatasetEntityStruct::class()
            ),
        ]);
        static::assertEquals(1, $collection->count());
    }

    public function testBuildingMappedStruct(): void
    {
        $datasetEntity = new DatasetEntityStruct();
        $mapping = new MappingStruct(
            $this->createMock(PortalNodeKeyInterface::class),
            $this->createMock(MappingNodeKeyInterface::class),
            DatasetEntityStruct::class()
        );
        $struct = new MappedDatasetEntityStruct($mapping, $datasetEntity);

        static::assertEquals($mapping, $struct->getMapping());
        static::assertEquals($datasetEntity, $struct->getDatasetEntity());
    }

    public function testBuildingMappedCollection(): void
    {
        $collection = new MappedDatasetEntityCollection();
        $collection->push([
            new MappedDatasetEntityStruct($this->createMock(MappingInterface::class), new FirstEntity()),
            new MappedDatasetEntityStruct($this->createMock(MappingInterface::class), new FirstEntity()),
            new MappedDatasetEntityStruct($this->createMock(MappingInterface::class), new FirstEntity()),
        ]);
        static::assertCount(3, $collection);
    }

    public function testTypedMapping(): void
    {
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $mappingNodeKey = $this->createMock(MappingNodeKeyInterface::class);
        $collection = new MappingCollection([
            new MappingStruct($portalNodeKey, $mappingNodeKey, FirstEntity::class()),
        ]);
        $collection->push([
            new MappingStruct($portalNodeKey, $mappingNodeKey, DatasetEntityStruct::class()),
            new MappingStruct($portalNodeKey, $mappingNodeKey, FirstEntity::class()),
            new MappingStruct($portalNodeKey, $mappingNodeKey, SecondEntity::class()),
        ]);
        static::assertCount(4, $collection);
        /** @var array<class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>, \Heptacom\HeptaConnect\Storage\Base\TypedMappingCollection> $groupByType */
        $groupByType = \iterable_to_array($collection->groupByType());
        static::assertCount(3, $groupByType);
        static::assertArrayHasKey(DatasetEntityStruct::class, $groupByType);
        static::assertArrayHasKey(FirstEntity::class, $groupByType);
        static::assertArrayHasKey(SecondEntity::class, $groupByType);
        static::assertCount(1, $groupByType[DatasetEntityStruct::class]);
        static::assertCount(2, $groupByType[FirstEntity::class]);
        static::assertCount(1, $groupByType[SecondEntity::class]);
        static::assertTrue(DatasetEntityStruct::class()->same($groupByType[DatasetEntityStruct::class]->getEntityType()));
        static::assertTrue(FirstEntity::class()->same($groupByType[FirstEntity::class]->getEntityType()));
        static::assertTrue(SecondEntity::class()->same($groupByType[SecondEntity::class]->getEntityType()));
    }
}
