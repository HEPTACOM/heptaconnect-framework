<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

trait ProvidesBooleanTestsData
{
    /**
     * @return iterable<array-key, array<int, bool>>
     */
    public function provideValidBooleanTestCases(): iterable
    {
        yield [false];
        yield [true];
    }
}
