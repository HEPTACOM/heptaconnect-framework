<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Date;

trait ProvidesDateTestsData
{
    /**
     * @return iterable<array-key, array<int, Date>>
     */
    public function provideValidDateTestCases(): iterable
    {
        yield [new Date()];
        yield [new Date()];
        yield [new Date()];
        yield [new Date()];
        yield [new Date()];
    }
}
