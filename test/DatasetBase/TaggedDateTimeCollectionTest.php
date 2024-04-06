<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Utility\Collection\Contract\TagItem;
use Heptacom\HeptaConnect\Utility\Date\DateTimeCollection;
use Heptacom\HeptaConnect\Utility\Date\TaggedDateTimeCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Utility\Date\DateTimeCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection
 * @covers \Heptacom\HeptaConnect\Utility\Date\TaggedDateTimeCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Contract\TagItem
 */
final class TaggedDateTimeCollectionTest extends TestCase
{
    use ProvidesDateTimeTestsData;
    use ProvidesInvalidTestsData;

    /**
     * @dataProvider provideValidDateTimeTestCases
     */
    public function testInsertTagOnCtor(\DateTimeInterface $item): void
    {
        $tagged = new TaggedDateTimeCollection([new TagItem(new DateTimeCollection([$item]), 'randomoffset')]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    /**
     * @dataProvider provideValidDateTimeTestCases
     */
    public function testChangingCollection(\DateTimeInterface $item): void
    {
        $tagged = new TaggedDateTimeCollection();
        static::assertFalse($tagged->offsetExists('randomoffset'));
        static::assertCount(0, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertTrue($tagged->offsetExists('randomoffset'));
        $tagged->offsetGet('randomoffset')->setCollection(new DateTimeCollection([$item]));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
    }

    /**
     * @dataProvider provideValidDateTimeTestCases
     */
    public function testGetTagItemAfterInserting(\DateTimeInterface $item): void
    {
        $tagged = new TaggedDateTimeCollection();
        $tagged->offsetSet('randomoffset', new TagItem(new DateTimeCollection([$item]), 'randomoffset'));
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    /**
     * @dataProvider provideValidDateTimeTestCases
     */
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

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new TaggedDateTimeCollection();
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testFailInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\InvalidArgumentException::class);
        $collection = new TaggedDateTimeCollection();
        $collection->push([$item]);
    }
}
