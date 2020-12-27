<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

trait ProvidesInvalidTestsData
{
    public function provideInvalidTestCases(): iterable
    {
        yield [new \Exception()];
        yield [new \stdClass()];
        yield [new class() {
        }];
    }
}
