<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\MappingCollection;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\MappingStruct;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\MappingCollection
 */
class MappingDatasetEntityTest extends TestCase
{
    public function testBuildingMappedCollection(): void
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
}
