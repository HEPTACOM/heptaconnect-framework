<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection;
use Heptacom\HeptaConnect\Utility\Collection\Contract\TagItem;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\BooleanCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\TaggedBooleanCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractTaggedCollection::class)]
#[CoversClass(TagItem::class)]
#[CoversClass(BooleanCollection::class)]
#[CoversClass(TaggedBooleanCollection::class)]
final class TaggedBooleanCollectionTest extends TestCase
{
    use ProvidesBooleanTestsData;
    use ProvidesInvalidTestsData;

    #[DataProvider('provideValidBooleanTestCases')]
    public function testInsertTagOnCtor(bool $item): void
    {
        $tagged = new TaggedBooleanCollection([new TagItem(new BooleanCollection([$item]), 'randomoffset')]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    #[DataProvider('provideValidBooleanTestCases')]
    public function testChangingCollection(bool $item): void
    {
        $tagged = new TaggedBooleanCollection();
        static::assertFalse($tagged->offsetExists('randomoffset'));
        static::assertCount(0, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertTrue($tagged->offsetExists('randomoffset'));
        $tagged->offsetGet('randomoffset')->setCollection(new BooleanCollection([$item]));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
    }

    #[DataProvider('provideValidBooleanTestCases')]
    public function testGetTagItemAfterInserting(bool $item): void
    {
        $tagged = new TaggedBooleanCollection();
        $tagged->offsetSet('randomoffset', new TagItem(new BooleanCollection([$item]), 'randomoffset'));
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    #[DataProvider('provideValidBooleanTestCases')]
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

    #[DataProvider('provideInvalidTestCases')]
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new TaggedBooleanCollection();
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testFailInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\InvalidArgumentException::class);
        $collection = new TaggedBooleanCollection();
        $collection->push([$item]);
    }
}
