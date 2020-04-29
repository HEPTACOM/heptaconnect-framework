<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Struct;
use Heptacom\HeptaConnect\Dataset\Base\StructCollection;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationStruct;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\UsageStructCollection;
use PHPStan\Testing\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Struct
 * @covers \Heptacom\HeptaConnect\Dataset\Base\StructCollection
 */
class CollectionTest extends TestCase
{
    /**
     * @dataProvider provideValidItems
     */
    public function testPassingInValidItemsAndKeepAll(array $items): void
    {
        $collection = new UsageStructCollection();
        $collection->push(...$items);
        $this->assertCount($collection->count(), $items);
    }

    /**
     * @dataProvider provideInvalidTypeItems
     */
    public function testPassingInInvalidTypeItemsAndKeepNone(array $items): void
    {
        $collection = new UsageStructCollection();

        try {
            $collection->push(...$items);
            $this->fail('No type error');
        } catch (\TypeError $typeError) {
        }

        $this->assertNotCount($collection->count(), $items);
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @dataProvider provideInvalidObjectItems
     */
    public function testPassingInInvalidObjectItemsAndKeepNone(array $items): void
    {
        $collection = new UsageStructCollection();

        try {
            $collection->push(...$items);
        } catch (\TypeError $typeError) {
        }

        $this->assertNotCount($collection->count(), $items);
        $this->assertEquals(0, $collection->count());
    }

    public function testClear(): void
    {
        $collection = new UsageStructCollection();
        $collection->push(new SerializationStruct(), new SerializationStruct());
        $this->assertCount(2, $collection);
        $collection->clear();
        $this->assertCount(0, $collection);
    }

    public function testFirst(): void
    {
        $collection = new UsageStructCollection();
        $this->assertNull($collection->first());

        $struct = new SerializationStruct();
        $struct->publicString = 'testvaluetofind';
        $collection->push($struct, new SerializationStruct());
        $this->assertNotNull($collection->first());
        $this->assertEquals($collection->first()->publicString, 'testvaluetofind');
    }

    public function testLast(): void
    {
        $collection = new UsageStructCollection();
        $this->assertNull($collection->last());

        $struct = new SerializationStruct();
        $struct->publicString = 'testvaluetofind';
        $collection->push(new SerializationStruct(), $struct);
        $this->assertNotNull($collection->last());
        $this->assertEquals($collection->last()->publicString, 'testvaluetofind');
    }

    public function testIndexAccess(): void
    {
        $collection = new UsageStructCollection();
        $collection->offsetSet(799, new SerializationStruct());
        $this->assertTrue($collection->offsetExists(799));
        $this->assertNotNull($collection->offsetGet(799));
        $collection->offsetUnset(799);
        $this->assertNull($collection->offsetGet(799));
    }

    public function testGenerator(): void
    {
        $collection = new UsageStructCollection();
        $collection->push(new SerializationStruct());
        $this->assertIsIterable($collection->getIterator());
        $this->assertCount(1, $collection->getIterator());

        $collection->clear();
        $this->assertIsIterable($collection->getIterator());
        $this->assertCount(0, $collection->getIterator());
    }

    public function testSerialization(): void
    {
        $collection = new UsageStructCollection();
        $collection->push(new SerializationStruct());

        $coded = $this->codeIt($collection);
        $this->assertNotEmpty($coded);

        $collection->clear();
        $coded = $this->codeIt($collection);
        $this->assertEmpty($coded);
    }

    /**
     * @return iterable<string, array<int, Struct>>
     */
    public function provideValidItems(): iterable
    {
        yield SerializationStruct::class => [[new SerializationStruct()]];
    }

    /**
     * @return iterable<string, array<int, mixed>>
     */
    public function provideInvalidObjectItems(): iterable
    {
        yield \DateTime::class => [[new \DateTime()]];
        yield \DateInterval::class => [[new \DateInterval('P1D')]];
        yield \Exception::class => [[new \Exception()]];
    }

    /**
     * @return iterable<string, array<int, mixed>>
     */
    public function provideInvalidTypeItems(): iterable
    {
        yield 'string' => [['fancy']];
        yield 'int' => [[123]];
    }

    /**
     * @throws \JsonException
     */
    protected function codeIt(StructCollection $collection): array
    {
        $encoded = \json_encode($collection, \JSON_THROW_ON_ERROR);
        /** @var array<string, mixed> $decoded */
        $decoded = \json_decode($encoded, true, 512, \JSON_THROW_ON_ERROR);

        return $decoded;
    }
}
