<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

trait ProvidesFloatTestsData
{
    /**
     * @return iterable<array-key, array<int, float>>
     */
    public function provideValidFloatTestCases(): iterable
    {
        yield [0.0];
        yield [1.0];
        yield [\INF];
        yield [-\INF];
        yield [-5e6];
    }
}
