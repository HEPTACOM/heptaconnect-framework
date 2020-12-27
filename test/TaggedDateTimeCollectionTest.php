<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateTimeCollection;
use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedDateTimeCollection;
use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TagItem;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateTimeCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\AbstractTaggedCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedDateTimeCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TagItem
 */
class TaggedDateTimeCollectionTest extends TestCase
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
        $collection->push([$item]);
        static::assertCount(0, $collection);
    }
}
