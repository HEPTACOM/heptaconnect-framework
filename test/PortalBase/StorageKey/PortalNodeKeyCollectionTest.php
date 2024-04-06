<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\StorageKey;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\Portal;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection
 * @covers \Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey
 */
final class PortalNodeKeyCollectionTest extends TestCase
{
    public function testContains(): void
    {
        $collection = new PortalNodeKeyCollection();
        $key1 = new PreviewPortalNodeKey(Portal::class());
        $key2 = new PreviewPortalNodeKey(Portal::class());

        static::assertFalse($collection->contains($key1));
        static::assertFalse($collection->contains($key2));

        $collection->push([$key1]);

        static::assertTrue($collection->contains($key1));
        static::assertTrue($collection->contains($key2));
    }

    public function testUnique(): void
    {
        $collection = new PortalNodeKeyCollection();
        $key1 = new PreviewPortalNodeKey(Portal::class());
        $key2 = new PreviewPortalNodeKey(Portal::class());

        static::assertFalse($collection->contains($key1));
        static::assertFalse($collection->contains($key2));

        $collection->push([$key1, $key2]);

        static::assertTrue($collection->contains($key1));
        static::assertTrue($collection->contains($key2));
        static::assertCount(2, $collection);

        $unique = $collection->asUnique();

        static::assertTrue($unique->contains($key1));
        static::assertTrue($unique->contains($key2));
        static::assertCount(1, $unique);
    }
}
