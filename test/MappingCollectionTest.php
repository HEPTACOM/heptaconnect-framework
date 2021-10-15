<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\MappingCollection;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\DatasetEntityStruct;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\MappingStruct;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\SecondEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct
 * @covers \Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\MappingCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\TypedMappingCollection
 */
class MappingCollectionTest extends TestCase
{
    public function testBuildingMappingCollection(): void
    {
        $collection = new MappingCollection();
        $collection->push([
            new MappingStruct(
                $this->createMock(PortalNodeKeyInterface::class),
                $this->createMock(MappingNodeKeyInterface::class)
            ),
        ]);
        static::assertEquals(1, $collection->count());
    }

    public function testBuildingMappedStruct(): void
    {
        $datasetEntity = new DatasetEntityStruct();
        $mapping = new MappingStruct(
            $this->createMock(PortalNodeKeyInterface::class),
            $this->createMock(MappingNodeKeyInterface::class)
        );
        $struct = new MappedDatasetEntityStruct($mapping, $datasetEntity);

        static::assertEquals($mapping, $struct->getMapping());
        static::assertEquals($datasetEntity, $struct->getDatasetEntity());
    }

    public function testBuildingMappedCollection(): void
    {
        $collection = new MappedDatasetEntityCollection();
        $collection->push([
            $this->createMock(MappedDatasetEntityStruct::class),
            $this->createMock(MappedDatasetEntityStruct::class),
            $this->createMock(MappedDatasetEntityStruct::class),
        ]);
        static::assertCount(3, $collection);
    }

    public function testTypedMapping(): void
    {
        $datasetEntityMapping = $this->createMock(MappingStruct::class);
        $datasetEntityMapping->method('getEntityType')->willReturn(DatasetEntityStruct::class);

        $firstMapping = $this->createMock(MappingStruct::class);
        $firstMapping->method('getEntityType')->willReturn(FirstEntity::class);

        $secondMapping = $this->createMock(MappingStruct::class);
        $secondMapping->method('getEntityType')->willReturn(SecondEntity::class);

        $collection = new MappingCollection([$firstMapping]);
        $collection->push([
            $datasetEntityMapping,
            $firstMapping,
            $secondMapping,
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
        static::assertEquals(DatasetEntityStruct::class, $groupByType[DatasetEntityStruct::class]->getType());
        static::assertEquals(FirstEntity::class, $groupByType[FirstEntity::class]->getType());
        static::assertEquals(SecondEntity::class, $groupByType[SecondEntity::class]->getType());
    }

    public function testTypedMappedDatasetEntityCollection(): void
    {
        $datasetEntityMapping = $this->createMock(MappingStruct::class);
        $datasetEntityMapping->method('getEntityType')->willReturn(DatasetEntityStruct::class);
        $dataset = $this->createMock(MappedDatasetEntityStruct::class);
        $dataset->method('getMapping')->willReturn($datasetEntityMapping);

        $firstMapping = $this->createMock(MappingStruct::class);
        $firstMapping->method('getEntityType')->willReturn(FirstEntity::class);
        $first = $this->createMock(MappedDatasetEntityStruct::class);
        $first->method('getMapping')->willReturn($firstMapping);

        $secondMapping = $this->createMock(MappingStruct::class);
        $secondMapping->method('getEntityType')->willReturn(SecondEntity::class);
        $second = $this->createMock(MappedDatasetEntityStruct::class);
        $second->method('getMapping')->willReturn($secondMapping);

        $collection = new TypedMappedDatasetEntityCollection(FirstEntity::class, [$first]);
        $collection->push([
            $dataset,
            $first,
            $second,
        ]);
        static::assertCount(2, $collection);
        static::assertEquals(FirstEntity::class, $collection->getType());
    }
}
