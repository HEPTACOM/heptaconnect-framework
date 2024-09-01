<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Test\Php;

use Heptacom\HeptaConnect\Utility\ClassString\UnsafeClassString;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use Heptacom\HeptaConnect\Utility\Php\ClassCodeHasher;
use Heptacom\HeptaConnect\Utility\Php\SetStateTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ClassCodeHasher::class)]
final class ClassCodeHasherTest extends TestCase
{
    public function testHashClass(): void
    {
        static::assertNotSame('', ClassCodeHasher::getInstance()->hashClassStringCode(new UnsafeClassString(\DateTime::class)));
    }

    public function testHashInterface(): void
    {
        static::assertNotSame('', ClassCodeHasher::getInstance()->hashClassStringCode(new UnsafeClassString(\DateTimeInterface::class)));
    }

    public function testHashTrait(): void
    {
        static::assertNotSame('', ClassCodeHasher::getInstance()->hashClassStringCode(new UnsafeClassString(SetStateTrait::class)));
    }

    public function testHashClassContainsInterfaceHash(): void
    {
        static::assertStringContainsString(
            ClassCodeHasher::getInstance()->hashClassStringCode(new UnsafeClassString(\DateTimeInterface::class)),
            ClassCodeHasher::getInstance()->hashClassStringCode(new UnsafeClassString(\DateTime::class)),
        );
    }

    public function testHashClassContainsTraitHash(): void
    {
        static::assertStringContainsString(
            ClassCodeHasher::getInstance()->hashClassStringCode(new UnsafeClassString(SetStateTrait::class)),
            ClassCodeHasher::getInstance()->hashClassStringCode(new UnsafeClassString(AbstractCollection::class)),
        );
    }

    public function testHashClassContainsParentClassHash(): void
    {
        static::assertStringContainsString(
            ClassCodeHasher::getInstance()->hashClassStringCode(new UnsafeClassString(AbstractCollection::class)),
            ClassCodeHasher::getInstance()->hashClassStringCode(new UnsafeClassString(AbstractObjectCollection::class)),
        );
    }
}
