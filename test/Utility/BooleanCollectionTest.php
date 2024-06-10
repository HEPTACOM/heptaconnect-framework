<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\BooleanCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractCollection::class)]
#[CoversClass(BooleanCollection::class)]
final class BooleanCollectionTest extends TestCase
{
    use ProvidesBooleanTestsData;

    #[DataProvider('provideValidBooleanTestCases')]
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
