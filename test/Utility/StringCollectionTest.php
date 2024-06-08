<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

use Heptacom\HeptaConnect\Utility\Collection\Scalar\StringCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Scalar\StringCollection
 */
final class StringCollectionTest extends TestCase
{
    use ProvidesInvalidTestsData;
    use ProvidesStringTestsData;

    /**
     * @dataProvider provideValidStringTestCases
     */
    public function testInsertTypeInTypeCollection(string $item): void
    {
        $collection = new StringCollection();
        static::assertFalse($collection->contains($item));
        $collection->push([$item]);
        static::assertCount(1, $collection);
        static::assertEquals($item, $collection[0]);
        static::assertTrue($collection->contains($item));
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new StringCollection();
        static::assertFalse($collection->contains($item));
        $collection->pushIgnoreInvalidItems([$item]);
        static::assertCount(0, $collection);
        static::assertFalse($collection->contains($item));
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
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