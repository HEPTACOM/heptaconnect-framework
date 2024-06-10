<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

trait ProvidesStringTestsData
{
    /**
     * @return iterable<array-key, array<int, string>>
     */
    public static function provideValidStringTestCases(): iterable
    {
        yield ['Hello'];
        yield ['World'];
    }
}
