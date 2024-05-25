<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection;
use Heptacom\HeptaConnect\Utility\Collection\Contract\TagItem;
use Heptacom\HeptaConnect\Utility\Date\Date;
use Heptacom\HeptaConnect\Utility\Date\DateCollection;
use Heptacom\HeptaConnect\Utility\Date\TaggedDateCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractTaggedCollection::class)]
#[CoversClass(TagItem::class)]
#[CoversClass(DateCollection::class)]
#[CoversClass(TaggedDateCollection::class)]
final class TaggedDateCollectionTest extends TestCase
{
    use ProvidesDateTestsData;
    use ProvidesInvalidTestsData;

    #[DataProvider('provideValidDateTestCases')]
    public function testInsertTagOnCtor(Date $item): void
    {
        $tagged = new TaggedDateCollection([new TagItem(new DateCollection([$item]), 'randomoffset')]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    #[DataProvider('provideValidDateTestCases')]
    public function testChangingCollection(Date $item): void
    {
        $tagged = new TaggedDateCollection();
        static::assertFalse($tagged->offsetExists('randomoffset'));
        static::assertCount(0, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertTrue($tagged->offsetExists('randomoffset'));
        $tagged->offsetGet('randomoffset')->setCollection(new DateCollection([$item]));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
    }

    #[DataProvider('provideValidDateTestCases')]
    public function testGetTagItemAfterInserting(Date $item): void
    {
        $tagged = new TaggedDateCollection();
        $tagged->offsetSet('randomoffset', new TagItem(new DateCollection([$item]), 'randomoffset'));
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    #[DataProvider('provideValidDateTestCases')]
    public function testGetTagItemWithoutInsertingItFirst(Date $item): void
    {
        $tagged = new TaggedDateCollection();
        $tagged->offsetGet('randomoffset')->getCollection()->push([$item]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    public function testEnforceNumericKeysBeStrings(): void
    {
        $tagged = new TaggedDateCollection();
        static::assertEquals('1', $tagged->offsetGet(1)->getTag());
        static::assertTrue($tagged->offsetExists('1'));
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new TaggedDateCollection();
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testFailInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\InvalidArgumentException::class);
        $collection = new TaggedDateCollection();
        $collection->push([$item]);
    }
}
