<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Dependency;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntity
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\PrimaryKeyTrait
 */
class AttachmentTest extends TestCase
{
    public function testStructAttachmentsAreEmptyByDefault(): void
    {
        $struct = new SerializationDatasetEntity();
        static::assertEquals(0, $struct->getAttachments()->count());
    }

    public function testStructAllowDuplicateValuesInAttchementsButNotWithTheSameKey(): void
    {
        $struct = new SerializationDatasetEntity();
        $struct->attach(new SerializationDatasetEntity());
        static::assertEquals(1, $struct->getAttachments()->count());
        static::assertTrue($struct->hasAttached(SerializationDatasetEntity::class));
        static::assertFalse($struct->hasAttached(Dependency::class));
        static::assertNotNull($struct->getAttachment(SerializationDatasetEntity::class));
        static::assertNull($struct->getAttachment(Dependency::class));
    }
}
