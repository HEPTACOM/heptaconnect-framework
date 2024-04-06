<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ForeignKeyAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\File\FileReferenceCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\ForeignKeyTrait;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\AttachmentA;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\AttachmentAChild;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Utility\Attachment\AttachmentCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\ForeignKeyTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\PrimaryKeyTrait
 */
final class AttachmentTest extends TestCase
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
        static::assertFalse($struct->hasAttached(FileReferenceCollection::class));
        static::assertNotNull($struct->getAttachment(SerializationDatasetEntity::class));
        static::assertNull($struct->getAttachment(FileReferenceCollection::class));
    }

    public function testDetachByType(): void
    {
        $struct = new SerializationDatasetEntity();
        $struct->attach(new SerializationDatasetEntity());
        static::assertCount(1, $struct->getAttachments());
        $struct->detachByType(SerializationDatasetEntity::class);
        static::assertCount(0, $struct->getAttachments());
    }

    public function testDetach(): void
    {
        $struct = new SerializationDatasetEntity();
        $struct->attach(new SerializationDatasetEntity());
        static::assertCount(1, $struct->getAttachments());
        $struct->detachByType(SerializationDatasetEntity::class);
        static::assertCount(0, $struct->getAttachments());
    }

    public function testNonExistingClasses(): void
    {
        $struct = new SerializationDatasetEntity();
        $struct->attach(new SerializationDatasetEntity());
        static::assertCount(1, $struct->getAttachments());
        static::assertFalse($struct->hasAttached('Unknown🙃Class'));
        $struct->detachByType('Unknown🙃Class');
        static::assertCount(1, $struct->getAttachments());
        static::assertNull($struct->getAttachment('Unknown🙃Class'));
        static::assertCount(1, $struct->getAttachments());
    }

    public function testKeySynchronizationInAttachments(): void
    {
        $prime = new SerializationDatasetEntity();
        $second = new class() extends SerializationDatasetEntity implements ForeignKeyAwareInterface {
            use ForeignKeyTrait;

            public function getForeignEntityType(): EntityType
            {
                return SerializationDatasetEntity::class();
            }
        };
        $prime->attach($second);
        $third = new class() extends SerializationDatasetEntity implements ForeignKeyAwareInterface {
            use ForeignKeyTrait;

            public function getForeignEntityType(): EntityType
            {
                return (new class() extends DatasetEntityContract {
                })::class();
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

    public function testAttachmentOrderDoesNotMatter(): void
    {
        $attachableAware = new SerializationDatasetEntity();
        $attachment1 = new AttachmentA();
        $attachment2 = new AttachmentAChild();

        static::assertCount(0, $attachableAware->getAttachments());
        static::assertTrue($attachableAware->getAttachments()->isEmpty());

        $attachableAware->attach($attachment1);

        static::assertCount(1, $attachableAware->getAttachments());
        static::assertFalse($attachableAware->getAttachments()->isEmpty());

        $attachableAware->attach($attachment2);

        static::assertCount(2, $attachableAware->getAttachments());
        static::assertFalse($attachableAware->getAttachments()->isEmpty());

        $attachableAware->getAttachments()->clear();

        static::assertCount(0, $attachableAware->getAttachments());
        static::assertTrue($attachableAware->getAttachments()->isEmpty());

        $attachableAware->attach($attachment2);

        static::assertCount(1, $attachableAware->getAttachments());
        static::assertFalse($attachableAware->getAttachments()->isEmpty());

        $attachableAware->attach($attachment1);

        static::assertCount(2, $attachableAware->getAttachments());
        static::assertFalse($attachableAware->getAttachments()->isEmpty());
    }

    public function testAttachmentTypeChecksCanBeNonAttachableInterfaces(): void
    {
        $attachableAware = new SerializationDatasetEntity();
        $attachment1 = new AttachmentA();
        $attachment2 = new AttachmentAChild();

        $attachableAware->attach($attachment1);
        $attachableAware->attach($attachment2);

        static::assertCount(2, $attachableAware->getAttachments());
        static::assertTrue($attachableAware->hasAttached(LoggerInterface::class));
        static::assertSame($attachment1, $attachableAware->getAttachment(LoggerInterface::class));

        $attachableAware->detachByType(LoggerInterface::class);

        static::assertCount(0, $attachableAware->getAttachments());
    }
}
