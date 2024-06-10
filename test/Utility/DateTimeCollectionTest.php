<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Date\DateTimeCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractCollection::class)]
#[CoversClass(DateTimeCollection::class)]
final class DateTimeCollectionTest extends TestCase
{
    use ProvidesDateTimeTestsData;
    use ProvidesInvalidTestsData;

    #[DataProvider('provideValidDateTimeTestCases')]
    public function testInsertTypeInTypeCollection(\DateTimeInterface $item): void
    {
        $collection = new DateTimeCollection();
        static::assertFalse($collection->contains($item));
        $collection->push([$item]);
        static::assertCount(1, $collection);
        static::assertEquals($item, $collection[0]);
        static::assertTrue($collection->contains($item));
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new DateTimeCollection();
        static::assertFalse($collection->contains($item));
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
        static::assertFalse($collection->contains($item));
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testFailInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\InvalidArgumentException::class);
        $collection = new DateTimeCollection();
        $collection->push([$item]);
    }
}
