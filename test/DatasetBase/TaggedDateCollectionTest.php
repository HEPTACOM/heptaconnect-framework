<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Date;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateCollection;
use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedDateCollection;
use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TagItem;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\AbstractTaggedCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedDateCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TagItem
 */
class TaggedDateCollectionTest extends TestCase
{
    use ProvidesDateTestsData;
    use ProvidesInvalidTestsData;

    /**
     * @dataProvider provideValidDateTestCases
     */
    public function testInsertTagOnCtor(Date $item): void
    {
        $tagged = new TaggedDateCollection([new TagItem(new DateCollection([$item]), 'randomoffset')]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    /**
     * @dataProvider provideValidDateTestCases
     */
    public function testChangingCollection(Date $item): void
    {
        $tagged = new TaggedDateCollection();
        static::assertFalse($tagged->offsetExists('randomoffset'));
        static::assertCount(0, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertTrue($tagged->offsetExists('randomoffset'));
        $tagged->offsetGet('randomoffset')->setCollection(new DateCollection([$item]));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
    }

    /**
     * @dataProvider provideValidDateTestCases
     */
    public function testGetTagItemAfterInserting(Date $item): void
    {
        $tagged = new TaggedDateCollection();
        $tagged->offsetSet('randomoffset', new TagItem(new DateCollection([$item]), 'randomoffset'));
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    /**
     * @dataProvider provideValidDateTestCases
     */
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

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new TaggedDateCollection();
        $collection->push([$item]);
        static::assertCount(0, $collection);
    }
}
