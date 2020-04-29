<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Struct;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationStruct;
use PHPStan\Testing\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Struct
 */
class StructTest extends TestCase
{
    /**
     * @dataProvider provideStructs
     */
    public function testSerializationAccessors(Struct $struct): void
    {
        $deserializedData = $this->codeIt($struct);

        $this->assertArrayNotHasKey('privateString', $deserializedData);
        $this->assertArrayHasKey('protectedString', $deserializedData);
        $this->assertArrayHasKey('publicString', $deserializedData);
    }

    /**
     * @depends testSerializationAccessors
     * @dataProvider provideStructs
     */
    public function testSerializationTypes(Struct $struct): void
    {
        $deserializedData = $this->codeIt($struct);

        $this->assertIsString($deserializedData['publicString']);
        $this->assertIsString($deserializedData['publicDateTime']);
        $this->assertIsInt($deserializedData['publicInt']);
        $this->assertIsFloat($deserializedData['publicFloat']);
    }

    /**
     * @return iterable<string, array<int, Struct>>
     */
    public function provideStructs(): iterable
    {
        yield SerializationStruct::class => [new SerializationStruct()];
    }

    /**
     * @throws \JsonException
     */
    protected function codeIt(Struct $struct): array
    {
        $encoded = \json_encode($struct, \JSON_THROW_ON_ERROR);
        /** @var array<string, mixed> $decoded */
        $decoded = \json_decode($encoded, true, 512, \JSON_THROW_ON_ERROR);

        return $decoded;
    }
}
