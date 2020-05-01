<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityCollectionInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\UsageStructCollection;
use PHPStan\Testing\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntity
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 */
class CollectionTest extends TestCase
{
    /**
     * @dataProvider provideValidItems
     *
     * @param array<int, mixed> $items
     */
    public function testPassingInValidItemsAndKeepAll(array $items): void
    {
        $collection = new UsageStructCollection();
        $collection->push(...$items);
        $this->assertCount($collection->count(), $items);
    }

    /**
     * @dataProvider provideInvalidTypeItems
     *
     * @param array<int, mixed> $items
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
        $collection->push(new SerializationDatasetEntity(), new SerializationDatasetEntity());
        $this->assertCount(2, $collection);
        $collection->clear();
        $this->assertCount(0, $collection);
    }

    public function testFirst(): void
    {
        $collection = new UsageStructCollection();
        $this->assertNull($collection->first());

        $struct = new SerializationDatasetEntity();
        $struct->publicString = 'testvaluetofind';
        $collection->push($struct, new SerializationDatasetEntity());
        $this->assertNotNull($collection->first());

        $firstItem = $collection->first();
        $this->assertInstanceOf(SerializationDatasetEntity::class, $firstItem);
        $this->assertEquals($firstItem->publicString, 'testvaluetofind');
    }

    public function testLast(): void
    {
        $collection = new UsageStructCollection();
        $this->assertNull($collection->last());

        $struct = new SerializationDatasetEntity();
        $struct->publicString = 'testvaluetofind';
        $collection->push(new SerializationDatasetEntity(), $struct);
        $this->assertNotNull($collection->last());

        $lastItem = $collection->last();
        $this->assertInstanceOf(SerializationDatasetEntity::class, $lastItem);
        $this->assertEquals($lastItem->publicString, 'testvaluetofind');
    }

    public function testIndexAccess(): void
    {
        $collection = new UsageStructCollection();
        $collection->offsetSet(799, new SerializationDatasetEntity());
        $this->assertTrue($collection->offsetExists(799));
        $this->assertNotNull($collection->offsetGet(799));
        $collection->offsetUnset(799);
        $this->assertNull($collection->offsetGet(799));
    }

    public function testGenerator(): void
    {
        $collection = new UsageStructCollection();
        $collection->push(new SerializationDatasetEntity());
        $this->assertIsIterable($collection->getIterator());
        $this->assertCount(1, $collection->getIterator());

        $collection->clear();
        $this->assertIsIterable($collection->getIterator());
        $this->assertCount(0, $collection->getIterator());
    }

    public function testSerialization(): void
    {
        $collection = new UsageStructCollection();
        $collection->push(new SerializationDatasetEntity());

        $coded = $this->codeIt($collection);
        $this->assertNotEmpty($coded);

        $collection->clear();
        $coded = $this->codeIt($collection);
        $this->assertEmpty($coded);
    }

    /**
     * @return iterable<string, array<int, array<int, DatasetEntityInterface>>>
     */
    public function provideValidItems(): iterable
    {
        yield SerializationDatasetEntity::class => [[new SerializationDatasetEntity()]];
    }

    /**
     * @return iterable<string, array<int, array<int, object>>>
     */
    public function provideInvalidObjectItems(): iterable
    {
        yield \DateTime::class => [[new \DateTime()]];
        yield \DateInterval::class => [[new \DateInterval('P1D')]];
        yield \Exception::class => [[new \Exception()]];
    }

    /**
     * @return iterable<string, array<int, array<int, mixed>>>
     */
    public function provideInvalidTypeItems(): iterable
    {
        yield 'string' => [['fancy']];
        yield 'int' => [[123]];
    }

    /**
     * @throws \JsonException
     */
    protected function codeIt(DatasetEntityCollectionInterface $collection): array
    {
        $encoded = \json_encode($collection, \JSON_THROW_ON_ERROR);
        /** @var array<string, mixed> $decoded */
        $decoded = \json_decode($encoded, true, 512, \JSON_THROW_ON_ERROR);

        return $decoded;
    }
}
