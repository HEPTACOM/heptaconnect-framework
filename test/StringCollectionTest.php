<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection
 */
class StringCollectionTest extends TestCase
{
    use ProvidesInvalidTestsData;
    use ProvidesStringTestsData;

    /**
     * @dataProvider provideValidStringTestCases
     */
    public function testInsertTypeInTypeCollection(string $item): void
    {
        $collection = new StringCollection();
        $collection->push([$item]);
        static::assertCount(1, $collection);
        static::assertEquals($item, $collection[0]);
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new StringCollection();
        $collection->push([$item]);
        static::assertCount(0, $collection);
    }
}
