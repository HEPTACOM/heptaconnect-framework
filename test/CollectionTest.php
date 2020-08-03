<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\UsageStructCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntity
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\JsonSerializeObjectVarsTrait
 */
class CollectionTest extends TestCase
{
    /**
     * @dataProvider provideValidItems
     *
     * @param array<int, DatasetEntityInterface> $items
     */
    public function testPassingInValidItemsAndKeepAll(array $items): void
    {
        $collection = new UsageStructCollection($items);
        static::assertCount($collection->count(), $items);
    }

    public function testClear(): void
    {
        $collection = new UsageStructCollection();
        $collection->push([new SerializationDatasetEntity(), new SerializationDatasetEntity()]);
        static::assertCount(2, $collection);
        $collection->clear();
        static::assertCount(0, $collection);
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
        static::assertCount(1, $collection->getIterator());

        $collection->clear();
        static::assertCount(0, $collection->getIterator());
    }

    public function testSerialization(): void
    {
        $collection = new UsageStructCollection();
        $collection->push([new SerializationDatasetEntity()]);

        $coded = $this->codeIt($collection);
        static::assertNotEmpty($coded);

        $collection->clear();
        $coded = $this->codeIt($collection);
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
        static::assertCount(0, $collection->filter(fn (SerializationDatasetEntity $entity) => $entity->publicInt === 0));
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
     * @throws \JsonException
     */
    protected function codeIt(UsageStructCollection $collection): array
    {
        $encoded = \json_encode($collection, \JSON_THROW_ON_ERROR);
        /** @var array<string, mixed> $decoded */
        $decoded = \json_decode($encoded, true, 512, \JSON_THROW_ON_ERROR);

        return $decoded;
    }
}
