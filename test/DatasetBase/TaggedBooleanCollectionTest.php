<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Utility\Collection\Contract\TagItem;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\BooleanCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\TaggedBooleanCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Contract\TagItem
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Scalar\BooleanCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Scalar\TaggedBooleanCollection
 */
final class TaggedBooleanCollectionTest extends TestCase
{
    use ProvidesBooleanTestsData;
    use ProvidesInvalidTestsData;

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testInsertTagOnCtor(bool $item): void
    {
        $tagged = new TaggedBooleanCollection([new TagItem(new BooleanCollection([$item]), 'randomoffset')]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testChangingCollection(bool $item): void
    {
        $tagged = new TaggedBooleanCollection();
        static::assertFalse($tagged->offsetExists('randomoffset'));
        static::assertCount(0, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertTrue($tagged->offsetExists('randomoffset'));
        $tagged->offsetGet('randomoffset')->setCollection(new BooleanCollection([$item]));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
    }

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testGetTagItemAfterInserting(bool $item): void
    {
        $tagged = new TaggedBooleanCollection();
        $tagged->offsetSet('randomoffset', new TagItem(new BooleanCollection([$item]), 'randomoffset'));
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testGetTagItemWithoutInsertingItFirst(bool $item): void
    {
        $tagged = new TaggedBooleanCollection();
        $tagged->offsetGet('randomoffset')->getCollection()->push([$item]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    public function testEnforceNumericKeysBeStrings(): void
    {
        $tagged = new TaggedBooleanCollection();
        static::assertEquals('1', $tagged->offsetGet(1)->getTag());
        static::assertTrue($tagged->offsetExists('1'));
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new TaggedBooleanCollection();
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testFailInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\InvalidArgumentException::class);
        $collection = new TaggedBooleanCollection();
        $collection->push([$item]);
    }
}
