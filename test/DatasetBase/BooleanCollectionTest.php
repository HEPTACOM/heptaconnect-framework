<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Utility\Collection\Scalar\BooleanCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\Scalar\BooleanCollection
 */
final class BooleanCollectionTest extends TestCase
{
    use ProvidesBooleanTestsData;

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testInsertTypeInTypeCollection(bool $item): void
    {
        $collection = new BooleanCollection();
        static::assertFalse($collection->contains($item));
        $collection->push([$item]);
        static::assertCount(1, $collection);
        static::assertEquals($item, $collection[0]);
        static::assertTrue($collection->contains($item));
    }
}
