<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Date;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\DateCollection
 */
class DateCollectionTest extends TestCase
{
    use ProvidesDateTestsData;
    use ProvidesInvalidTestsData;

    /**
     * @dataProvider provideValidDateTestCases
     */
    public function testInsertTypeInTypeCollection(Date $item): void
    {
        $collection = new DateCollection();
        $collection->push([$item]);
        static::assertCount(1, $collection);
        static::assertEquals($item, $collection[0]);
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new DateCollection();
        $collection->push([$item]);
        static::assertCount(0, $collection);
    }
}
