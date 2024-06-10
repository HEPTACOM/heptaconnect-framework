<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

trait ProvidesFloatTestsData
{
    /**
     * @return iterable<array-key, array<int, float>>
     */
    public static function provideValidFloatTestCases(): iterable
    {
        yield [0.0];
        yield [1.0];
        yield [\INF];
        yield [-\INF];
        yield [-5e6];
    }
}
