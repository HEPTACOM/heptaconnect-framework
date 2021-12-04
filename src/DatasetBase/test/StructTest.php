<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\JsonSerializeObjectVarsTrait
 */
class StructTest extends TestCase
{
    /**
     * @dataProvider provideStructs
     */
    public function testSerializationAccessors(DatasetEntityContract $struct): void
    {
        $deserializedData = $this->codeIt($struct);

        static::assertArrayNotHasKey('privateString', $deserializedData);
        static::assertArrayHasKey('protectedString', $deserializedData);
        static::assertArrayHasKey('publicString', $deserializedData);
    }

    /**
     * @depends testSerializationAccessors
     * @dataProvider provideStructs
     */
    public function testSerializationTypes(DatasetEntityContract $struct): void
    {
        $deserializedData = $this->codeIt($struct);

        static::assertIsString($deserializedData['publicString']);
        static::assertIsString($deserializedData['publicDateTime']);
        static::assertIsInt($deserializedData['publicInt']);
        static::assertIsFloat($deserializedData['publicFloat']);
        static::assertIsString($deserializedData['primaryKey']);
        static::assertEquals($deserializedData['primaryKey'], $struct->getPrimaryKey());
    }

    /**
     * @return iterable<string, array<int, DatasetEntityContract>>
     */
    public function provideStructs(): iterable
    {
        $struct = new SerializationDatasetEntity();
        $struct->setPrimaryKey('the primary key of choice');

        yield SerializationDatasetEntity::class => [$struct];
    }

    /**
     * @throws \JsonException
     */
    protected function codeIt(DatasetEntityContract $struct): array
    {
        $encoded = \json_encode($struct, \JSON_THROW_ON_ERROR);
        /** @var array<string, mixed> $decoded */
        $decoded = \json_decode($encoded, true, 512, \JSON_THROW_ON_ERROR);

        return $decoded;
    }
}
