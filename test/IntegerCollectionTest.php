<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\IntegerCollection
 */
class IntegerCollectionTest extends TestCase
{
    use ProvidesIntegerTestsData;

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testInsertTypeInTypeCollection(int $item): void
    {
        $collection = new IntegerCollection();
        $collection->push([$item]);
        static::assertCount(1, $collection);
        static::assertEquals($item, $collection[0]);
    }
}
