<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ForeignKeyAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Dependency;
use Heptacom\HeptaConnect\Dataset\Base\Support\ForeignKeyTrait;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\ForeignKeyTrait
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

    public function testUnattach(): void
    {
        $struct = new SerializationDatasetEntity();
        $struct->attach(new SerializationDatasetEntity());
        static::assertEquals(1, $struct->getAttachments()->count());
        $struct->unattach(SerializationDatasetEntity::class);
        static::assertEquals(0, $struct->getAttachments()->count());
    }

    public function testNonExistingClasses(): void
    {
        $struct = new SerializationDatasetEntity();
        $struct->attach(new SerializationDatasetEntity());
        static::assertEquals(1, $struct->getAttachments()->count());
        static::assertFalse($struct->hasAttached('Unknown🙃Class'));
        $struct->unattach('Unknown🙃Class');
        static::assertEquals(1, $struct->getAttachments()->count());
        static::assertNull($struct->getAttachment('Unknown🙃Class'));
        static::assertEquals(1, $struct->getAttachments()->count());
    }

    public function testKeySynchronizationInAttachments(): void
    {
        $prime = new SerializationDatasetEntity();
        $second = new class() extends SerializationDatasetEntity implements ForeignKeyAwareInterface {
            use ForeignKeyTrait;

            public function getForeignEntityType(): string
            {
                return SerializationDatasetEntity::class;
            }
        };
        $prime->attach($second);
        $third = new class() extends SerializationDatasetEntity implements ForeignKeyAwareInterface {
            use ForeignKeyTrait;

            public function getForeignEntityType(): string
            {
                return DatasetEntityContract::class;
            }
        };
        $prime->attach($third);
        $fourth = new class() extends SerializationDatasetEntity {
            public function getForeignEntityType(): string
            {
                return SerializationDatasetEntity::class;
            }
        };
        $prime->attach($fourth);

        $second->setForeignKey(\bin2hex(\random_bytes(32)));
        $third->setForeignKey($oldThird = \bin2hex(\random_bytes(32)));
        $prime->setPrimaryKey(\bin2hex(\random_bytes(32)));

        static::assertSame($prime->getPrimaryKey(), $second->getForeignKey());
        static::assertNotSame($prime->getPrimaryKey(), $third->getForeignKey());
        static::assertSame($oldThird, $third->getForeignKey());
    }
}
