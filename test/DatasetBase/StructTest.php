<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Json\JsonSerializeObjectVarsTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(DatasetEntityCollection::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversTrait(JsonSerializeObjectVarsTrait::class)]
final class StructTest extends TestCase
{
    #[DataProvider('provideStructs')]
    public function testSerializationAccessors(DatasetEntityContract $struct): void
    {
        $deserializedData = $this->codeIt($struct);

        static::assertArrayNotHasKey('privateString', $deserializedData);
        static::assertArrayHasKey('protectedString', $deserializedData);
        static::assertArrayHasKey('publicString', $deserializedData);
    }

    #[Depends('testSerializationAccessors')]
    #[DataProvider('provideStructs')]
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
    public static function provideStructs(): iterable
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
