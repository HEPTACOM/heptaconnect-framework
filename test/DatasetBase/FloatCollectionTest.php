<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\FloatCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\FloatCollection
 */
final class FloatCollectionTest extends TestCase
{
    use ProvidesFloatTestsData;
    use ProvidesInvalidTestsData;

    /**
     * @dataProvider provideValidFloatTestCases
     */
    public function testInsertTypeInTypeCollection(float $item): void
    {
        $collection = new FloatCollection();
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
        $collection = new FloatCollection();
        $collection->push([$item]);
        static::assertCount(0, $collection);
        static::assertNull($collection->max());
        static::assertNull($collection->min());
        static::assertNull($collection->sum());
    }

    public function testAggregate(): void
    {
        $collection = new FloatCollection();

        foreach ($this->provideValidFloatTestCases() as [$value]) {
            $collection->push([$value]);
        }

        static::assertSame(\INF, $collection->max());
        static::assertSame(-\INF, $collection->min());
        static::assertNan($collection->sum());
    }
}
