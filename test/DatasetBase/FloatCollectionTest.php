<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\FloatCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\FloatCollection
 */
class FloatCollectionTest extends TestCase
{
    use ProvidesFloatTestsData;
    use ProvidesInvalidTestsData;

    /**
     * @dataProvider provideValidFloatTestCases
     */
    public function testInsertTypeInTypeCollection(float $item): void
    {
        $collection = new FloatCollection();
        $collection->push([$item]);
        static::assertCount(1, $collection);
        static::assertEquals($item, $collection[0]);
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new FloatCollection();
        $collection->push([$item]);
        static::assertCount(0, $collection);
    }
}
