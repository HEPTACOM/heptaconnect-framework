<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\DatasetBase\Test;

use Heptacom\HeptaConnect\Dataset\Base\UnsafeClassString;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\UnsafeClassString
 */
final class UnsafeClassStringTest extends TestCase
{
    public function testNoExceptionOnInvalidClassString(): void
    {
        static::assertSame('foo bar', (new UnsafeClassString('foo bar'))->getClassString());
    }

    public function testPrefixStripping(): void
    {
        static::assertSame('foo bar', (new UnsafeClassString('\foo bar'))->getClassString());
        static::assertSame('\foo bar', (new UnsafeClassString('\foo bar'))->getAbsoluteClassString());
    }

    public function testComparison(): void
    {
        $foobarWithLeadingNsSep = new UnsafeClassString('\foo bar');
        $foobar = new UnsafeClassString('foo bar');
        $notFoobar = new UnsafeClassString('gizmo');
        static::assertTrue($foobar->equals($foobar));
        static::assertTrue($foobarWithLeadingNsSep->equals($foobar));
        static::assertFalse($notFoobar->equals($foobar));
        static::assertTrue($foobar->same($foobar));
        static::assertFalse($foobarWithLeadingNsSep->same($foobar));
        static::assertFalse($notFoobar->same($foobar));
    }

    public function testOriginalInputCanBeFetched(): void
    {
        $foobarWithLeadingNsSep = new UnsafeClassString('\foo bar');
        static::assertSame('\foo bar', (string) $foobarWithLeadingNsSep);
        static::assertSame('\foo bar', \json_decode(\json_encode($foobarWithLeadingNsSep)));
    }
}
