<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\UsageStructCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\JsonSerializeObjectVarsTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\SetStateTrait
 */
final class CollectionTest extends TestCase
{
    use ProvidesJsonSerializer;

    /**
     * @dataProvider provideValidItems
     *
     * @param array<int, DatasetEntityContract> $items
     */
    public function testPassingInValidItemsAndKeepAll(array $items): void
    {
        $collection = new UsageStructCollection($items);
        static::assertCount($collection->count(), $items);
    }

    /**
     * @dataProvider provideInvalidObjectItems
     *
     * @param array<int, DatasetEntityContract> $items
     */
    public function testPassingInInvalidItemsAndKeepAll(array $items): void
    {
        $collection = new UsageStructCollection();
        $collection->pushIgnoreInvalidItems($items);
        static::assertEquals(0, $collection->count());

        foreach ($items as $key => $item) {
            $collection->offsetSet($key, $item);
        }

        static::assertEquals(0, $collection->count());

        foreach ($items as $key => $item) {
            $collection[$key] = $item;
        }

        static::assertEquals(0, $collection->count());
    }

    public function testClear(): void
    {
        $collection = new UsageStructCollection();
        $collection->push([new SerializationDatasetEntity(), new SerializationDatasetEntity()]);
        static::assertCount(2, $collection);
        static::assertFalse($collection->isEmpty());
        $collection->clear();
        static::assertCount(0, $collection);
        static::assertTrue($collection->isEmpty());
    }

    public function testFirst(): void
    {
        $collection = new UsageStructCollection();
        static::assertNull($collection->first());

        $struct = new SerializationDatasetEntity();
        $struct->publicString = 'testvaluetofind';
        $collection->push([$struct, new SerializationDatasetEntity()]);
        static::assertNotNull($collection->first());

        $firstItem = $collection->first();
        static::assertInstanceOf(SerializationDatasetEntity::class, $firstItem);
        static::assertEquals($firstItem->publicString, 'testvaluetofind');
    }

    public function testLast(): void
    {
        $collection = new UsageStructCollection();
        static::assertNull($collection->last());

        $struct = new SerializationDatasetEntity();
        $struct->publicString = 'testvaluetofind';
        $collection->push([new SerializationDatasetEntity(), $struct]);
        static::assertNotNull($collection->last());

        $lastItem = $collection->last();
        static::assertInstanceOf(SerializationDatasetEntity::class, $lastItem);
        static::assertEquals($lastItem->publicString, 'testvaluetofind');
    }

    public function testIndexAccess(): void
    {
        $collection = new UsageStructCollection();
        $collection->offsetSet(799, new SerializationDatasetEntity());
        static::assertTrue($collection->offsetExists(799));
        static::assertNotNull($collection->offsetGet(799));
        $collection->offsetUnset(799);
        static::assertNull($collection->offsetGet(799));
    }

    public function testGenerator(): void
    {
        $collection = new UsageStructCollection();
        $collection->push([new SerializationDatasetEntity()]);
        static::assertCount(1, \iterable_to_array($collection->getIterator()));
        static::assertFalse($collection->isEmpty());

        $collection->clear();
        static::assertCount(0, \iterable_to_array($collection->getIterator()));
        static::assertTrue($collection->isEmpty());
    }

    public function testSerialization(): void
    {
        $collection = new UsageStructCollection();
        $collection->push([new SerializationDatasetEntity()]);
        $collection->offsetSet(17, new SerializationDatasetEntity());
        $collection[42] = new SerializationDatasetEntity();

        $coded = $this->jsonEncodeAndDecode($collection);
        static::assertNotEmpty($coded);
        static::assertEquals([
            [
                'publicString' => 'public',
                'publicDateTime' => '2010-11-20T14:30:50+00:00',
                'publicInt' => 42,
                'publicFloat' => 13.37,
                'protectedString' => 'protected',
                'attachments' => null,
                'dependencies' => null,
                'primaryKey' => null,
            ],
            [
                'publicString' => 'public',
                'publicDateTime' => '2010-11-20T14:30:50+00:00',
                'publicInt' => 42,
                'publicFloat' => 13.37,
                'protectedString' => 'protected',
                'attachments' => null,
                'dependencies' => null,
                'primaryKey' => null,
            ],
            [
                'publicString' => 'public',
                'publicDateTime' => '2010-11-20T14:30:50+00:00',
                'publicInt' => 42,
                'publicFloat' => 13.37,
                'protectedString' => 'protected',
                'attachments' => null,
                'dependencies' => null,
                'primaryKey' => null,
            ],
        ], $coded);

        static::assertFalse($collection->isEmpty());
        $collection->clear();
        static::assertTrue($collection->isEmpty());
        $coded = $this->jsonEncodeAndDecode($collection);
        static::assertEmpty($coded);
    }

    public function testFilter(): void
    {
        $collection = new UsageStructCollection();
        $changedEntity = new SerializationDatasetEntity();
        ++$changedEntity->publicInt;
        $collection->push([
            new SerializationDatasetEntity(),
            $changedEntity,
        ]);

        static::assertCount(2, $collection);
        static::assertCount(0, \iterable_to_array($collection->filter(fn (SerializationDatasetEntity $entity) => $entity->publicInt === 0)));
    }

    public function testSetState(): void
    {
        $collection = UsageStructCollection::__set_state([
            'items' => [
                new SerializationDatasetEntity(),
                new SerializationDatasetEntity(),
                new class() extends SerializationDatasetEntity {
                },
            ],
        ]);
        static::assertCount(3, $collection);
    }

    public function testChunking(): void
    {
        $items = \range(1, 96);
        $collection = new IntegerCollection($items);
        $chunks = \iterable_to_array($collection->chunk(30));
        $chunks = \array_map('iterable_to_array', $chunks);
        static::assertCount(30, $chunks[0]);
        static::assertCount(30, $chunks[1]);
        static::assertCount(30, $chunks[2]);
        static::assertCount(6, $chunks[3]);
        static::assertSame(\array_chunk($items, 30), $chunks);

        static::assertSame([], \iterable_to_array((new IntegerCollection())->chunk(10)));

        $chunks = \iterable_to_array((new IntegerCollection([1, 2, 3]))->chunk(-5));
        $chunks = \array_map('iterable_to_array', $chunks);

        static::assertSame([[1], [2], [3]], $chunks);
    }

    public function testToArray(): void
    {
        $items = \range(1, 26);
        $collection = new IntegerCollection($items);
        static::assertSame($items, $collection->asArray());
    }

    public function testReverse(): void
    {
        $items = \range(1, 26);
        $collection = new IntegerCollection($items);
        $items = \range(26, 1, -1);
        $collection->reverse();
        static::assertSame($items, $collection->asArray());
    }

    public function testUnique(): void
    {
        $items = \range(1, 26);
        $collection = new IntegerCollection($items);
        $collection->push(\range(26, 1, -1));

        static::assertCount(52, $collection);

        $unique = $collection->asUnique();

        static::assertCount(26, $unique);
    }

    /**
     * @return iterable<string, array<int, array<int, DatasetEntityContract>>>
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
}
