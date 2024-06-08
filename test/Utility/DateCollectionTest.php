<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

use Heptacom\HeptaConnect\Utility\Date\Date;
use Heptacom\HeptaConnect\Utility\Date\DateCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Date\DateCollection
 */
final class DateCollectionTest extends TestCase
{
    use ProvidesDateTestsData;
    use ProvidesInvalidTestsData;

    /**
     * @dataProvider provideValidDateTestCases
     */
    public function testInsertTypeInTypeCollection(Date $item): void
    {
        $collection = new DateCollection();
        static::assertFalse($collection->contains($item));
        $collection->push([$item]);
        static::assertCount(1, $collection);
        static::assertEquals($item, $collection[0]);
        static::assertTrue($collection->contains($item));
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new DateCollection();
        static::assertFalse($collection->contains($item));
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
        static::assertFalse($collection->contains($item));
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testFailInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\InvalidArgumentException::class);
        $collection = new DateCollection();
        $collection->push([$item]);
    }
}