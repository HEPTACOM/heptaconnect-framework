<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableDateTime;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\GenericTranslatable
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableDateTime
 */
class TranslatableDateTimeTest extends TestCase
{
    /**
     * @dataProvider provideValidTestCases
     */
    public function testInsertTypeInTypeTranslatable(\DateTimeInterface $item): void
    {
        $translatable = new TranslatableDateTime();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $item);
        static::assertEquals($item, $translatable->getTranslation($localeKey));
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testArrayNotationInsertTypeInTypeTranslatable(\DateTimeInterface $item): void
    {
        $translatable = new TranslatableDateTime();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $item;
        static::assertEquals($item, $translatable[$localeKey]);
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testRemovalViaNullValue(\DateTimeInterface $anyValue): void
    {
        $translatable = new TranslatableDateTime();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->getTranslation($localeKey));
        $translatable->removeTranslation($localeKey);
        static::assertNull($translatable->getTranslation($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testRemovalViaArrayNullAssignment(\DateTimeInterface $anyValue): void
    {
        $translatable = new TranslatableDateTime();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $anyValue;
        static::assertEquals($anyValue, $translatable[$localeKey]);
        $translatable[$localeKey] = null;
        static::assertNull($translatable[$localeKey]);
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testRemovalViaUnset(\DateTimeInterface $anyValue): void
    {
        $translatable = new TranslatableDateTime();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $anyValue;
        static::assertEquals($anyValue, $translatable[$localeKey]);
        static::assertTrue(isset($translatable[$localeKey]));
        unset($translatable[$localeKey]);
        static::assertNull($translatable[$localeKey]);
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testAccessViaOffset(\DateTimeInterface $anyValue): void
    {
        $translatable = new TranslatableDateTime();
        $localeKey = 'en-GB';
        $translatable->offsetSet($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->offsetGet($localeKey));
        static::assertTrue($translatable->offsetExists($localeKey));
        $translatable->offsetUnset($localeKey);
        static::assertNull($translatable->offsetGet($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testChainableCalls(\DateTimeInterface $anyValue): void
    {
        $translatable = new TranslatableDateTime();
        static::assertEquals($translatable, $translatable->setTranslation('en-GB', $anyValue));
        static::assertEquals($translatable, $translatable->removeTranslation('en-GB'));
    }

    /**
     * @return iterable<array-key, array<int, \DateTimeInterface>>
     */
    public function provideValidTestCases(): iterable
    {
        yield [new \DateTime()];
    }
}
