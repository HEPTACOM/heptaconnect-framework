<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\StringCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractCollection::class)]
#[CoversClass(StringCollection::class)]
final class StringCollectionTest extends TestCase
{
    use ProvidesInvalidTestsData;
    use ProvidesStringTestsData;

    #[DataProvider('provideValidStringTestCases')]
    public function testInsertTypeInTypeCollection(string $item): void
    {
        $collection = new StringCollection();
        static::assertFalse($collection->contains($item));
        $collection->push([$item]);
        static::assertCount(1, $collection);
        static::assertEquals($item, $collection[0]);
        static::assertTrue($collection->contains($item));
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new StringCollection();
        static::assertFalse($collection->contains($item));
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
        static::assertFalse($collection->contains($item));
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testFailInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\InvalidArgumentException::class);
        $collection = new StringCollection();
        $collection->push([$item]);
    }

    public function testJoin(): void
    {
        $collection = new StringCollection(['php', 'is', 'nice']);
        static::assertSame('php;is;nice', $collection->join(';'));
    }
}
