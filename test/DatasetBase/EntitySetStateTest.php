<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Json\JsonSerializeObjectVarsTrait;
use Heptacom\HeptaConnect\Utility\Php\SetStateTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(DatasetEntityCollection::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(JsonSerializeObjectVarsTrait::class)]
#[CoversClass(SetStateTrait::class)]
final class EntitySetStateTest extends TestCase
{
    #[DataProvider('provideStructs')]
    public function testSerializationTypes(SerializationDatasetEntity $struct): void
    {
        /** @var SerializationDatasetEntity $deserializedData */
        $deserializedData = $this->codeIt($struct);

        static::assertEquals($struct->getPublicString(), $deserializedData->getPublicString());
        static::assertEquals($struct->getPublicDateTime(), $deserializedData->getPublicDateTime());
        static::assertEquals($struct->getPublicInt(), $deserializedData->getPublicInt());
        static::assertEquals($struct->getPublicFloat(), $deserializedData->getPublicFloat());
        static::assertEquals($struct->getPrimaryKey(), $deserializedData->getPrimaryKey());
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

    protected function codeIt(DatasetEntityContract $struct): DatasetEntityContract
    {
        $result = null;
        eval(\sprintf('$result = %s;', \var_export($struct, true)));

        return $result;
    }
}
