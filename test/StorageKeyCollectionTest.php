<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\WebhookKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\MappingNodeStorageKeyCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeStorageKeyCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\WebhookStorageKeyCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\MappingNodeStorageKeyCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeStorageKeyCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\StorageKey\WebhookStorageKeyCollection
 */
class StorageKeyCollectionTest extends TestCase
{
    public function testMappingNodeStorageKeyCollection(): void
    {
        $collection = new MappingNodeStorageKeyCollection();
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

    public function testPortalNodeStorageKeyCollection(): void
    {
        $collection = new PortalNodeStorageKeyCollection();
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

    public function testWebhookStorageKeyCollection(): void
    {
        $collection = new WebhookStorageKeyCollection();
        $collection->push([new class() implements WebhookKeyInterface {
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
