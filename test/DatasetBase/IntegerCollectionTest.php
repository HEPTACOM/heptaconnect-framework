<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection
 */
final class IntegerCollectionTest extends TestCase
{
    use ProvidesIntegerTestsData;
    use ProvidesInvalidTestsData;

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testInsertTypeInTypeCollection(int $item): void
    {
        $collection = new IntegerCollection();
        $collection->push([$item]);
        static::assertCount(1, $collection);
        static::assertEquals($item, $collection[0]);
        static::assertSame($item, $collection->max());
        static::assertSame($item, $collection->min());
        static::assertSame($item, $collection->sum());
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new IntegerCollection();
        $collection->push([$item]);
        static::assertCount(0, $collection);
        static::assertNull($collection->max());
        static::assertNull($collection->min());
        static::assertSame(0, $collection->sum());
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
