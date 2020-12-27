<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

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
