<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection;
use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedIntegerCollection;
use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TagItem;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\AbstractTaggedCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedIntegerCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TagItem
 */
class TaggedIntegerCollectionTest extends TestCase
{
    use ProvidesIntegerTestsData;
    use ProvidesInvalidTestsData;

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testInsertTagOnCtor(int $item): void
    {
        $tagged = new TaggedIntegerCollection([new TagItem(new IntegerCollection([$item]), 'randomoffset')]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testChangingCollection(int $item): void
    {
        $tagged = new TaggedIntegerCollection();
        static::assertFalse($tagged->offsetExists('randomoffset'));
        static::assertCount(0, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertTrue($tagged->offsetExists('randomoffset'));
        $tagged->offsetGet('randomoffset')->setCollection(new IntegerCollection([$item]));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
    }

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testGetTagItemAfterInserting(int $item): void
    {
        $tagged = new TaggedIntegerCollection();
        $tagged->offsetSet('randomoffset', new TagItem(new IntegerCollection([$item]), 'randomoffset'));
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testGetTagItemWithoutInsertingItFirst(int $item): void
    {
        $tagged = new TaggedIntegerCollection();
        $tagged->offsetGet('randomoffset')->getCollection()->push([$item]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    public function testEnforceNumericKeysBeStrings(): void
    {
        $tagged = new TaggedIntegerCollection();
        static::assertEquals('1', $tagged->offsetGet(1)->getTag());
        static::assertTrue($tagged->offsetExists('1'));
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new TaggedIntegerCollection();
        $collection->push([$item]);
        static::assertCount(0, $collection);
    }
}
