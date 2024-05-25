<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test;

trait ProvidesInvalidTestsData
{
    public static function provideInvalidTestCases(): iterable
    {
        yield [new \Exception()];
        yield [new \stdClass()];
        yield [new class() {
        }];
    }
}
