<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableBooleanCollection;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableDateCollection;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableDateTimeCollection;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableFloatCollection;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableIntegerCollection;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableStringCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\AbstractTranslatableScalarCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableBooleanCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableDateCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableDateTimeCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableFloatCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableIntegerCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\ScalarCollection\TranslatableStringCollection
 */
class TranslatableScalarCollectionTest extends TestCase
{
    /**
     * @dataProvider provideTranslatableCollections
     */
    public function testNonNullableFallback(AbstractTranslatable $translatable): void
    {
        self::assertNotNull($translatable->getFallback());
        self::assertNotNull($translatable->getTranslation('any-key', true));
        self::assertNotNull($translatable->getTranslation('any-key', false));
    }

    public function provideTranslatableCollections(): iterable
    {
        yield [new TranslatableBooleanCollection()];
        yield [new TranslatableDateCollection()];
        yield [new TranslatableDateTimeCollection()];
        yield [new TranslatableFloatCollection()];
        yield [new TranslatableIntegerCollection()];
        yield [new TranslatableStringCollection()];
    }
}
