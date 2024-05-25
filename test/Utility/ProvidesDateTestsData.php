<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

use Heptacom\HeptaConnect\Utility\Date\Date;

trait ProvidesDateTestsData
{
    /**
     * @return iterable<array-key, array<int, Date>>
     */
    public static function provideValidDateTestCases(): iterable
    {
        yield [new Date()];
        yield [new Date()];
        yield [new Date()];
        yield [new Date()];
        yield [new Date()];
    }
}
