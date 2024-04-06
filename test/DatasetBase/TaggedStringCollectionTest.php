<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Utility\Collection\Contract\TagItem;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\StringCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\TaggedStringCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Scalar\StringCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Scalar\TaggedStringCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Contract\TagItem
 */
final class TaggedStringCollectionTest extends TestCase
{
    use ProvidesInvalidTestsData;
    use ProvidesStringTestsData;

    /**
     * @dataProvider provideValidStringTestCases
     */
    public function testInsertTagOnCtor(string $item): void
    {
        $tagged = new TaggedStringCollection([new TagItem(new StringCollection([$item]), 'randomoffset')]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    /**
     * @dataProvider provideValidStringTestCases
     */
    public function testChangingCollection(string $item): void
    {
        $tagged = new TaggedStringCollection();
        static::assertFalse($tagged->offsetExists('randomoffset'));
        static::assertCount(0, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertTrue($tagged->offsetExists('randomoffset'));
        $tagged->offsetGet('randomoffset')->setCollection(new StringCollection([$item]));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
    }

    /**
     * @dataProvider provideValidStringTestCases
     */
    public function testGetTagItemAfterInserting(string $item): void
    {
        $tagged = new TaggedStringCollection();
        $tagged->offsetSet('randomoffset', new TagItem(new StringCollection([$item]), 'randomoffset'));
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    /**
     * @dataProvider provideValidStringTestCases
     */
    public function testGetTagItemWithoutInsertingItFirst(string $item): void
    {
        $tagged = new TaggedStringCollection();
        $tagged->offsetGet('randomoffset')->getCollection()->push([$item]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    public function testEnforceNumericKeysBeStrings(): void
    {
        $tagged = new TaggedStringCollection();
        static::assertEquals('1', $tagged->offsetGet(1)->getTag());
        static::assertTrue($tagged->offsetExists('1'));
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new TaggedStringCollection();
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testFailInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\InvalidArgumentException::class);
        $collection = new TaggedStringCollection();
        $collection->push([$item]);
    }
}
