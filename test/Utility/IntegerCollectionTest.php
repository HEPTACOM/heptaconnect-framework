<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\IntegerCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractCollection::class)]
#[CoversClass(IntegerCollection::class)]
final class IntegerCollectionTest extends TestCase
{
    use ProvidesIntegerTestsData;
    use ProvidesInvalidTestsData;

    #[DataProvider('provideValidIntegerTestCases')]
    public function testInsertTypeInTypeCollection(int $item): void
    {
        $collection = new IntegerCollection();
        static::assertFalse($collection->contains($item));
        $collection->push([$item]);
        static::assertCount(1, $collection);
        static::assertEquals($item, $collection[0]);
        static::assertTrue($collection->contains($item));
        static::assertSame($item, $collection->max());
        static::assertSame($item, $collection->min());
        static::assertSame($item, $collection->sum());
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new IntegerCollection();
        static::assertFalse($collection->contains($item));
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
        static::assertFalse($collection->contains($item));
        static::assertNull($collection->max());
        static::assertNull($collection->min());
        static::assertSame(0, $collection->sum());
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testFailInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\InvalidArgumentException::class);
        $collection = new IntegerCollection();
        $collection->push([$item]);
    }

    public function testAggregate(): void
    {
        $collection = new IntegerCollection();

        foreach ($this->provideValidIntegerTestCases() as [$value]) {
            $collection->push([$value]);
        }

        static::assertSame(922337203685477580, $collection->max());
        static::assertSame(-922337203685477580, $collection->min());
        static::assertSame(-994, $collection->sum());
    }
}
