<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\AbstractTranslatableScalarCollection;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableBooleanCollection;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableDateCollection;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableDateTimeCollection;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableFloatCollection;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableIntegerCollection;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableStringCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractTranslatable::class)]
#[CoversClass(AbstractTranslatableScalarCollection::class)]
#[CoversClass(TranslatableBooleanCollection::class)]
#[CoversClass(TranslatableDateCollection::class)]
#[CoversClass(TranslatableDateTimeCollection::class)]
#[CoversClass(TranslatableFloatCollection::class)]
#[CoversClass(TranslatableIntegerCollection::class)]
#[CoversClass(TranslatableStringCollection::class)]
#[CoversClass(AbstractCollection::class)]
final class TranslatableScalarCollectionTest extends TestCase
{
    #[DataProvider('provideTranslatableCollections')]
    public function testNonNullableFallback(AbstractTranslatable $translatable): void
    {
        static::assertNotNull($translatable->getFallback());
        static::assertNotNull($translatable->getTranslation('any-key', true));
        static::assertNotNull($translatable->getTranslation('any-key', false));
    }

    public static function provideTranslatableCollections(): iterable
    {
        yield [new TranslatableBooleanCollection()];
        yield [new TranslatableDateCollection()];
        yield [new TranslatableDateTimeCollection()];
        yield [new TranslatableFloatCollection()];
        yield [new TranslatableIntegerCollection()];
        yield [new TranslatableStringCollection()];
    }
}
