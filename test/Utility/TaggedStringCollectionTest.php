<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\Contract\AbstractTaggedCollection;
use Heptacom\HeptaConnect\Utility\Collection\Contract\TagItem;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\StringCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\TaggedStringCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractTaggedCollection::class)]
#[CoversClass(TagItem::class)]
#[CoversClass(StringCollection::class)]
#[CoversClass(TaggedStringCollection::class)]
final class TaggedStringCollectionTest extends TestCase
{
    use ProvidesInvalidTestsData;
    use ProvidesStringTestsData;

    #[DataProvider('provideValidStringTestCases')]
    public function testInsertTagOnCtor(string $item): void
    {
        $tagged = new TaggedStringCollection([new TagItem(new StringCollection([$item]), 'randomoffset')]);
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    #[DataProvider('provideValidStringTestCases')]
    public function testChangingCollection(string $item): void
    {
        $tagged = new TaggedStringCollection();
        static::assertFalse($tagged->offsetExists('randomoffset'));
        static::assertCount(0, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertTrue($tagged->offsetExists('randomoffset'));
        $tagged->offsetGet('randomoffset')->setCollection(new StringCollection([$item]));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
    }

    #[DataProvider('provideValidStringTestCases')]
    public function testGetTagItemAfterInserting(string $item): void
    {
        $tagged = new TaggedStringCollection();
        $tagged->offsetSet('randomoffset', new TagItem(new StringCollection([$item]), 'randomoffset'));
        static::assertTrue($tagged->offsetExists('randomoffset'));
        static::assertCount(1, $tagged->offsetGet('randomoffset')->getCollection());
        static::assertEquals($item, $tagged->offsetGet('randomoffset')->getCollection()->first());
    }

    #[DataProvider('provideValidStringTestCases')]
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

    #[DataProvider('provideInvalidTestCases')]
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new TaggedStringCollection();
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testFailInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\InvalidArgumentException::class);
        $collection = new TaggedStringCollection();
        $collection->push([$item]);
    }
}
