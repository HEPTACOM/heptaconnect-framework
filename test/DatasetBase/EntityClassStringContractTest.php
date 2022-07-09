<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\DatasetBase\Test;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\EntityTypeClassString;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidClassNameException
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Exception\InvalidSubtypeClassNameException
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\EntityTypeClassString
 */
final class EntityClassStringContractTest extends TestCase
{
    public function testNoExceptionOnValidClassString(): void
    {
        static::assertSame(SerializationDatasetEntity::class, (string) SerializationDatasetEntity::class());
    }

    public function testExceptionOnInvalidClassString(): void
    {
        static::expectExceptionCode(1655559295);
        new EntityTypeClassString('foo\bar');
    }

    public function testExceptionOnInvalidSubClassString(): void
    {
        static::expectExceptionCode(1655559296);
        DatasetEntityContract::class();
    }

    public function testObjectTypeCheck(): void
    {
        $entity = new SerializationDatasetEntity();
        $subEntityClass = \get_class(new class() extends SerializationDatasetEntity {
        });

        static::assertTrue(SerializationDatasetEntity::class()->matchClassStringIsOfType(SerializationDatasetEntity::class()));
        static::assertTrue(SerializationDatasetEntity::class()->matchObjectIsOfType($entity));
        static::assertTrue(SerializationDatasetEntity::class()->matchObjectEqualsType($entity));
        static::assertTrue(SerializationDatasetEntity::class()->matchObjectSameType($entity));

        $subEntityClassString = $subEntityClass::class();

        static::assertFalse($subEntityClassString->matchClassStringIsOfType(SerializationDatasetEntity::class()));
        static::assertFalse($subEntityClassString->matchObjectIsOfType($entity));
        static::assertFalse($subEntityClassString->matchObjectEqualsType($entity));
        static::assertFalse($subEntityClassString->matchObjectSameType($entity));

        $subEntity = new $subEntityClass();

        static::assertTrue(SerializationDatasetEntity::class()->matchClassStringIsOfType($subEntityClass::class()));
        static::assertTrue(SerializationDatasetEntity::class()->matchObjectIsOfType($subEntity));
        static::assertFalse(SerializationDatasetEntity::class()->matchObjectEqualsType($subEntity));
        static::assertFalse(SerializationDatasetEntity::class()->matchObjectSameType($subEntity));

        static::assertTrue($subEntityClassString->matchClassStringIsOfType($subEntityClass::class()));
        static::assertTrue($subEntityClassString->matchObjectIsOfType($subEntity));
        static::assertTrue($subEntityClassString->matchObjectEqualsType($subEntity));
        static::assertTrue($subEntityClassString->matchObjectSameType($subEntity));
    }
}
