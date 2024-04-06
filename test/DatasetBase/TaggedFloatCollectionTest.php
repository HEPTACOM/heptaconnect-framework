<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Utility\Collection\Contract\TagItem;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\FloatCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\TaggedFloatCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Scalar\FloatCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Scalar\TaggedFloatCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Contract\TagItem
 */
final class TaggedFloatCollectionTest extends TestCase
{
    use ProvidesFloatTestsData;
    use ProvidesInvalidTestsData;

    /**
     * @dataProvider provideValidFloatTestCases
     */
    public function testInsertTagOnCtor(float $item): void
    {
        $tagged = new TaggedFloatCollection([new TagItem(new FloatCollection([$item]), 'randomoffset')]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    /**
     * @dataProvider provideValidFloatTestCases
     */
    public function testChangingCollection(float $item): void
    {
        $tagged = new TaggedFloatCollection();
        static::assertFalse($tagged->offsetExists('randomoffset'));
        static::assertCount(0, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertTrue($tagged->offsetExists('randomoffset'));
        $tagged->offsetGet('randomoffset')->setCollection(new FloatCollection([$item]));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
    }

    /**
     * @dataProvider provideValidFloatTestCases
     */
    public function testGetTagItemAfterInserting(float $item): void
    {
        $tagged = new TaggedFloatCollection();
        $tagged->offsetSet('randomoffset', new TagItem(new FloatCollection([$item]), 'randomoffset'));
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    /**
     * @dataProvider provideValidFloatTestCases
     */
    public function testGetTagItemWithoutInsertingItFirst(float $item): void
    {
        $tagged = new TaggedFloatCollection();
        $tagged->offsetGet('randomoffset')->getCollection()->push([$item]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    public function testEnforceNumericKeysBeStrings(): void
    {
        $tagged = new TaggedFloatCollection();
        static::assertEquals('1', $tagged->offsetGet(1)->getTag());
        static::assertTrue($tagged->offsetExists('1'));
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new TaggedFloatCollection();
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testFailInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\InvalidArgumentException::class);
        $collection = new TaggedFloatCollection();
        $collection->push([$item]);
    }
}
