<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\Contract\StorageMappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\StoragePortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\DatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\MappingStruct;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityStruct
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
            new MappedDatasetEntityStruct(
                new MappingStruct(
                    $this->createMock(StoragePortalNodeKeyInterface::class),
                    $this->createMock(StorageMappingNodeKeyInterface::class)
                ),
                new DatasetEntityStruct()
            ),
        ]);
        static::assertEquals(1, $collection->count());
    }
}
