<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\DatasetBase\Test;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use Heptacom\HeptaConnect\Utility\ClassString\ClassStringReferenceCollection;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\UnsafeClassString;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(ClassStringReferenceCollection::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(UnsafeClassString::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
final class UnsafeClassStringTest extends TestCase
{
    public function testNoExceptionOnInvalidClassString(): void
    {
        static::assertSame('foo bar', (string) (new UnsafeClassString('foo bar')));
    }

    public function testComparison(): void
    {
        $foobarWithLeadingNsSep = new UnsafeClassString('\foo bar');
        $foobar = new UnsafeClassString('foo bar');
        $notFoobar = new UnsafeClassString('gizmo');
        static::assertTrue($foobar->equals($foobar));
        static::assertFalse($foobarWithLeadingNsSep->equals($foobar));
        static::assertFalse($notFoobar->equals($foobar));
    }

    public function testOriginalInputCanBeFetched(): void
    {
        $foobarWithLeadingNsSep = new UnsafeClassString('\foo bar');
        static::assertSame('\foo bar', (string) $foobarWithLeadingNsSep);
        static::assertSame('\foo bar', \json_decode(\json_encode($foobarWithLeadingNsSep, \JSON_THROW_ON_ERROR), null, 512, \JSON_THROW_ON_ERROR));
    }

    public function testCollection(): void
    {
        $collection = new ClassStringReferenceCollection();
        $collection->push([
            SerializationDatasetEntity::class(),
            new UnsafeClassString('\foo bar'),
        ]);
        static::assertSame(2, $collection->count());
    }
}
