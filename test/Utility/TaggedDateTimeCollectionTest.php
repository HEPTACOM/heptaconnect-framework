<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection;
use Heptacom\HeptaConnect\Utility\Collection\Contract\TagItem;
use Heptacom\HeptaConnect\Utility\Date\DateTimeCollection;
use Heptacom\HeptaConnect\Utility\Date\TaggedDateTimeCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractTaggedCollection::class)]
#[CoversClass(TagItem::class)]
#[CoversClass(DateTimeCollection::class)]
#[CoversClass(TaggedDateTimeCollection::class)]
final class TaggedDateTimeCollectionTest extends TestCase
{
    use ProvidesDateTimeTestsData;
    use ProvidesInvalidTestsData;

    #[DataProvider('provideValidDateTimeTestCases')]
    public function testInsertTagOnCtor(\DateTimeInterface $item): void
    {
        $tagged = new TaggedDateTimeCollection([new TagItem(new DateTimeCollection([$item]), 'randomoffset')]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    #[DataProvider('provideValidDateTimeTestCases')]
    public function testChangingCollection(\DateTimeInterface $item): void
    {
        $tagged = new TaggedDateTimeCollection();
        static::assertFalse($tagged->offsetExists('randomoffset'));
        static::assertCount(0, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertTrue($tagged->offsetExists('randomoffset'));
        $tagged->offsetGet('randomoffset')->setCollection(new DateTimeCollection([$item]));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
    }

    #[DataProvider('provideValidDateTimeTestCases')]
    public function testGetTagItemAfterInserting(\DateTimeInterface $item): void
    {
        $tagged = new TaggedDateTimeCollection();
        $tagged->offsetSet('randomoffset', new TagItem(new DateTimeCollection([$item]), 'randomoffset'));
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    #[DataProvider('provideValidDateTimeTestCases')]
    public function testGetTagItemWithoutInsertingItFirst(\DateTimeInterface $item): void
    {
        $tagged = new TaggedDateTimeCollection();
        $tagged->offsetGet('randomoffset')->getCollection()->push([$item]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    public function testEnforceNumericKeysBeStrings(): void
    {
        $tagged = new TaggedDateTimeCollection();
        static::assertEquals('1', $tagged->offsetGet(1)->getTag());
        static::assertTrue($tagged->offsetExists('1'));
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new TaggedDateTimeCollection();
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testFailInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\InvalidArgumentException::class);
        $collection = new TaggedDateTimeCollection();
        $collection->push([$item]);
    }
}
