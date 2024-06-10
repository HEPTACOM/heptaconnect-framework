<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection;
use Heptacom\HeptaConnect\Utility\Collection\Contract\TagItem;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\FloatCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\TaggedFloatCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractTaggedCollection::class)]
#[CoversClass(TagItem::class)]
#[CoversClass(FloatCollection::class)]
#[CoversClass(TaggedFloatCollection::class)]
final class TaggedFloatCollectionTest extends TestCase
{
    use ProvidesFloatTestsData;
    use ProvidesInvalidTestsData;

    #[DataProvider('provideValidFloatTestCases')]
    public function testInsertTagOnCtor(float $item): void
    {
        $tagged = new TaggedFloatCollection([new TagItem(new FloatCollection([$item]), 'randomoffset')]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    #[DataProvider('provideValidFloatTestCases')]
    public function testChangingCollection(float $item): void
    {
        $tagged = new TaggedFloatCollection();
        static::assertFalse($tagged->offsetExists('randomoffset'));
        static::assertCount(0, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertTrue($tagged->offsetExists('randomoffset'));
        $tagged->offsetGet('randomoffset')->setCollection(new FloatCollection([$item]));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
    }

    #[DataProvider('provideValidFloatTestCases')]
    public function testGetTagItemAfterInserting(float $item): void
    {
        $tagged = new TaggedFloatCollection();
        $tagged->offsetSet('randomoffset', new TagItem(new FloatCollection([$item]), 'randomoffset'));
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    #[DataProvider('provideValidFloatTestCases')]
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

    #[DataProvider('provideInvalidTestCases')]
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new TaggedFloatCollection();
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testFailInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\InvalidArgumentException::class);
        $collection = new TaggedFloatCollection();
        $collection->push([$item]);
    }
}
