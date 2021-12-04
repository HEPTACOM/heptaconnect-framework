<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

trait ProvidesStringTestsData
{
    /**
     * @return iterable<array-key, array<int, string>>
     */
    public function provideValidStringTestCases(): iterable
    {
        yield ['Hello'];
        yield ['World'];
    }
}
