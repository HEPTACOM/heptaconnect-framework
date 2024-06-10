<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

trait ProvidesBooleanTestsData
{
    /**
     * @return iterable<array-key, array<int, bool>>
     */
    public static function provideValidBooleanTestCases(): iterable
    {
        yield [false];
        yield [true];
    }
}
