<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\BooleanCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\BooleanCollection
 */
class BooleanCollectionTest extends TestCase
{
    use ProvidesBooleanTestsData;

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testInsertTypeInTypeCollection(bool $item): void
    {
        $collection = new BooleanCollection();
        $collection->push([$item]);
        static::assertCount(1, $collection);
        static::assertEquals($item, $collection[0]);
    }
}
