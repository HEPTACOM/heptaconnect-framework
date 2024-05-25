<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\FloatCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractCollection::class)]
#[CoversClass(FloatCollection::class)]
final class FloatCollectionTest extends TestCase
{
    use ProvidesFloatTestsData;
    use ProvidesInvalidTestsData;

    #[DataProvider('provideValidFloatTestCases')]
    public function testInsertTypeInTypeCollection(float $item): void
    {
        $collection = new FloatCollection();
        static::assertFalse($collection->contains($item));
        $collection->push([$item]);
        static::assertCount(1, $collection);
        static::assertEquals($item, $collection[0]);
        static::assertSame($item, $collection->max());
        static::assertSame($item, $collection->min());
        static::assertSame($item, $collection->sum());
        static::assertTrue($collection->contains($item));
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new FloatCollection();
        static::assertFalse($collection->contains($item));
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
        static::assertFalse($collection->contains($item));
        static::assertNull($collection->max());
        static::assertNull($collection->min());
        static::assertSame(0.0, $collection->sum());
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testFailInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\InvalidArgumentException::class);
        $collection = new FloatCollection();
        $collection->push([$item]);
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
