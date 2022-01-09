<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage;

if (\class_exists(TestCase::class, false)) {
    return;
}

if (!\class_exists(\PHPUnit\Framework\TestCase::class, true)) {
    \trigger_error('Either install phpunit or provide a custom ' . TestCase::class, \E_USER_ERROR);
}

/**
 * You can define your own test case base class, when phpunit does not work for you.
 * Just ensure it is loaded before this class
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
}
