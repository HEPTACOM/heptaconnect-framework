<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\MappingNodeKeyCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\MappingNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 */
class StorageKeyCollectionTest extends TestCase
{
    public function testMappingNodeKeyCollection(): void
    {
        $collection = new MappingNodeKeyCollection();
        $collection->push([new class() implements MappingNodeKeyInterface {
            public function equals(StorageKeyInterface $other): bool
            {
                return \get_class($other) === \get_class($this);
            }

            public function jsonSerialize()
            {
                return [];
            }
        }]);
        static::assertCount(1, $collection);
    }

    public function testPortalNodeKeyCollection(): void
    {
        $collection = new PortalNodeKeyCollection();
        $collection->push([new class() implements PortalNodeKeyInterface {
            public function equals(StorageKeyInterface $other): bool
            {
                return \get_class($other) === \get_class($this);
            }

            public function jsonSerialize()
            {
                return [];
            }
        }]);
        static::assertCount(1, $collection);
    }
}
