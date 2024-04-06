<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

trait ProvidesDateTimeTestsData
{
    /**
     * @return iterable<array-key, array<int, \DateTimeInterface>>
     */
    public function provideValidDateTimeTestCases(): iterable
    {
        yield [new \DateTime()];
    }
}
