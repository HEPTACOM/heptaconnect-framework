<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\DatasetBase\Test;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityTypeClassString;
use Heptacom\HeptaConnect\Dataset\Base\EntityTypeClassStringCollection;
use Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidClassNameException;
use Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidSubtypeClassNameException;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use Heptacom\HeptaConnect\Dataset\Base\UnsafeClassString;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityTypeClassString
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityTypeClassStringCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidClassNameException
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidSubtypeClassNameException
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 */
final class EntityTypeClassStringTest extends TestCase
{
    public function testNoExceptionOnValidClassString(): void
    {
        static::assertSame(SerializationDatasetEntity::class, (string) SerializationDatasetEntity::class());
    }

    public function testExceptionOnInvalidClassString(): void
    {
        static::expectException(InvalidClassNameException::class);
        static::expectExceptionCode(1655559295);
        new EntityTypeClassString('foo\bar');
    }

    public function testExceptionOnInvalidSubClassString(): void
    {
        static::expectException(InvalidSubtypeClassNameException::class);
        static::expectExceptionCode(1655559296);
        DatasetEntityContract::class();
    }

    public function testObjectTypeCheck(): void
    {
        $entity = new SerializationDatasetEntity();
        $subEntityClass = \get_class(new class() extends SerializationDatasetEntity {
        });

        static::assertTrue(SerializationDatasetEntity::class()->isClassStringOfType(SerializationDatasetEntity::class()));
        static::assertTrue(SerializationDatasetEntity::class()->isObjectOfType($entity));
        static::assertTrue(SerializationDatasetEntity::class()->equalsObjectType($entity));
        static::assertTrue(SerializationDatasetEntity::class()->sameObjectType($entity));

        $subEntityClassString = $subEntityClass::class();

        static::assertFalse($subEntityClassString->isClassStringOfType(SerializationDatasetEntity::class()));
        static::assertFalse($subEntityClassString->isObjectOfType($entity));
        static::assertFalse($subEntityClassString->equalsObjectType($entity));
        static::assertFalse($subEntityClassString->sameObjectType($entity));

        $subEntity = new $subEntityClass();

        static::assertTrue(SerializationDatasetEntity::class()->isClassStringOfType($subEntityClass::class()));
        static::assertTrue(SerializationDatasetEntity::class()->isObjectOfType($subEntity));
        static::assertFalse(SerializationDatasetEntity::class()->equalsObjectType($subEntity));
        static::assertFalse(SerializationDatasetEntity::class()->sameObjectType($subEntity));

        static::assertTrue($subEntityClassString->isClassStringOfType($subEntityClass::class()));
        static::assertTrue($subEntityClassString->isObjectOfType($subEntity));
        static::assertTrue($subEntityClassString->equalsObjectType($subEntity));
        static::assertTrue($subEntityClassString->sameObjectType($subEntity));
    }

    public function testObjectNullAllowed(): void
    {
        static::assertFalse(SerializationDatasetEntity::class()->isObjectOfType(null));
        static::assertFalse(SerializationDatasetEntity::class()->equalsObjectType(null));
        static::assertFalse(SerializationDatasetEntity::class()->sameObjectType(null));
    }

    public function testCollection(): void
    {
        $collection = new EntityTypeClassStringCollection();
        $collection->push([
            SerializationDatasetEntity::class(),
        ]);
        static::assertSame(1, $collection->count());
        static::assertTrue($collection->has(SerializationDatasetEntity::class()));

        $subEntity = new class() extends SerializationDatasetEntity {
        };

        static::assertFalse($collection->has($subEntity::class()));
    }
}
