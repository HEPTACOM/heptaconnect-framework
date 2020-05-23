<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\Contract\StorageMappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\StoragePortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\MappingCollection;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\DatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\MappingStruct;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\SecondEntity;
use Heptacom\HeptaConnect\Portal\Base\TypedMappedDatasetEntityCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityStruct
 * @covers \Heptacom\HeptaConnect\Portal\Base\MappingCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\TypedMappedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\TypedMappingCollection
 */
class MappingCollectionTest extends TestCase
{
    public function testBuildingMappedStruct(): void
    {
        $datasetEntity = new DatasetEntityStruct();
        $mapping = new MappingStruct(
            $this->createMock(StoragePortalNodeKeyInterface::class),
            $this->createMock(StorageMappingNodeKeyInterface::class)
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
        $datasetEntityMapping->method('getDatasetEntityClassName')->willReturn(DatasetEntityStruct::class);

        $firstMapping = $this->createMock(MappingStruct::class);
        $firstMapping->method('getDatasetEntityClassName')->willReturn(FirstEntity::class);

        $secondMapping = $this->createMock(MappingStruct::class);
        $secondMapping->method('getDatasetEntityClassName')->willReturn(SecondEntity::class);

        $collection = new MappingCollection([$firstMapping]);
        $collection->push([
            $datasetEntityMapping,
            $firstMapping,
            $secondMapping,
        ]);
        static::assertCount(4, $collection);
        /** @var array<class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>, \Heptacom\HeptaConnect\Portal\Base\TypedMappingCollection> $groupByType */
        $groupByType = iterable_to_array($collection->groupByType());
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
        $datasetEntityMapping->method('getDatasetEntityClassName')->willReturn(DatasetEntityStruct::class);
        $dataset = $this->createMock(MappedDatasetEntityStruct::class);
        $dataset->method('getMapping')->willReturn($datasetEntityMapping);

        $firstMapping = $this->createMock(MappingStruct::class);
        $firstMapping->method('getDatasetEntityClassName')->willReturn(FirstEntity::class);
        $first = $this->createMock(MappedDatasetEntityStruct::class);
        $first->method('getMapping')->willReturn($firstMapping);

        $secondMapping = $this->createMock(MappingStruct::class);
        $secondMapping->method('getDatasetEntityClassName')->willReturn(SecondEntity::class);
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
