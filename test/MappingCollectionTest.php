<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\DatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\MappingStruct;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityStruct
 */
class MappedDatasetEntityTest extends TestCase
{
    public function testBuildingMappedStruct(): void
    {
        $datasetEntity = new DatasetEntityStruct();
        $mapping = new MappingStruct();
        $struct = new MappedDatasetEntityStruct($mapping, $datasetEntity);

        $this->assertEquals($mapping, $struct->getMapping());
        $this->assertEquals($datasetEntity, $struct->getDatasetEntity());
    }

    public function testBuildingMappedCollection(): void
    {
        $collection = new MappedDatasetEntityCollection();
        $collection->push(new MappedDatasetEntityStruct(new MappingStruct(), new DatasetEntityStruct()));
        $collection->push(new MappingStruct());
        $this->assertEquals(1, $collection->count());
    }
}
