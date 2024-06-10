<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\MappingNodeKeyCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MappingNodeKeyCollection::class)]
#[CoversClass(PortalNodeKeyCollection::class)]
#[CoversClass(AbstractCollection::class)]
final class StorageKeyCollectionTest extends TestCase
{
    public function testMappingNodeKeyCollection(): void
    {
        $collection = new MappingNodeKeyCollection();
        $collection->push([new class() implements MappingNodeKeyInterface {
            public function equals(StorageKeyInterface $other): bool
            {
                return $other::class === self::class;
            }

            #[\ReturnTypeWillChange]
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
                return $other::class === self::class;
            }

            #[\ReturnTypeWillChange]
            public function jsonSerialize()
            {
                return [];
            }

            public function withAlias(): PortalNodeKeyInterface
            {
                return $this;
            }

            public function withoutAlias(): PortalNodeKeyInterface
            {
                return $this;
            }
        }]);
        static::assertCount(1, $collection);
    }
}
