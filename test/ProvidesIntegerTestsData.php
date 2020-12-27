<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

trait ProvidesIntegerTestsData
{
    /**
     * @return iterable<array-key, array<int, int>>
     */
    public function provideValidIntegerTestCases(): iterable
    {
        yield [0];
        yield [1];
        yield [2];
        yield [3];
        yield [-1000];
        yield [922337203685477580];
        yield [-922337203685477580];
    }
}
