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
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\DependencyTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\JsonSerializeObjectVarsTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\SetStateTrait
 */
class EntitySetStateTest extends TestCase
{
    /**
     * @dataProvider provideStructs
     */
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
    public function provideStructs(): iterable
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
