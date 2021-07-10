<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableDateTime;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\SetStateTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableDateTime
 */
class TranslatableDateTimeTest extends TestCase
{
    use ProvidesDateTimeTestsData;
    use ProvidesInvalidTestsData;
    use ProvidesJsonSerializer;

    /**
     * @dataProvider provideValidDateTimeTestCases
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
     * @dataProvider provideValidDateTimeTestCases
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
     * @dataProvider provideValidDateTimeTestCases
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
     * @dataProvider provideValidDateTimeTestCases
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
     * @dataProvider provideValidDateTimeTestCases
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
     * @dataProvider provideValidDateTimeTestCases
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
     * @dataProvider provideValidDateTimeTestCases
     */
    public function testChainableCalls(\DateTimeInterface $anyValue): void
    {
        $translatable = new TranslatableDateTime();
        static::assertEquals($translatable, $translatable->setTranslation('en-GB', $anyValue));
        static::assertEquals($translatable, $translatable->removeTranslation('en-GB'));
    }

    /**
     * @dataProvider provideValidDateTimeTestCases
     */
    public function testAccessDenialViaNumericKey(\DateTimeInterface $anyValue): void
    {
        $translatable = new TranslatableDateTime();
        $localeKey = '1';
        $translatable->offsetSet($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->offsetGet($localeKey));
        static::assertTrue($translatable->offsetExists($localeKey));
        static::assertFalse($translatable->offsetExists(1));
        $translatable->offsetUnset($localeKey);
        static::assertNull($translatable->offsetGet($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidDateTimeTestCases
     */
    public function testSetState(\DateTimeInterface $anyValue): void
    {
        /** @var AbstractTranslatable $translatable */
        $translatable = TranslatableDateTime::__set_state([
            'translations' => [
                'en-GB' => $anyValue,
            ],
        ]);
        static::assertCount(1, $translatable->getLocaleKeys());
        static::assertEquals($anyValue, $translatable->getTranslation('en-GB'));
    }

    /**
     * @dataProvider provideValidDateTimeTestCases
     */
    public function testInvalidSetStateValues(\DateTimeInterface $anyValue): void
    {
        /** @var AbstractTranslatable $translatable */
        $translatable = TranslatableDateTime::__set_state([
            'translations' => [
                'en-GB' => $anyValue,
            ],
        ]);
        static::assertCount(1, $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $translatable = new TranslatableDateTime();
        $translatable->setTranslation('en-GB', $item);
        static::assertCount(0, $translatable->getLocaleKeys());
    }

    public function testSerialization(): void
    {
        $translatable = new TranslatableDateTime();
        $translatable->setTranslation('en-GB', new \DateTime('2010-11-20T14:30:50.000Z', new \DateTimeZone('UTC')));
        static::assertEquals(['en-GB' => [
            'date' => '2010-11-20 14:30:50.000000',
            'timezone_type' => \DateTimeZone::AMERICA,
            'timezone' => 'Z',
        ]], $this->jsonEncodeAndDecode($translatable));
    }

    /**
     * @dataProvider provideValidDateTimeTestCases
     */
    public function testValidFallback(\DateTimeInterface $anyValue): void
    {
        $translatable = new TranslatableDateTime();

        $translatable->setFallback($anyValue);
        static::assertSame($anyValue, $translatable->getFallback());
        $translatable->removeFallback();
        static::assertNull($translatable->getFallback());
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInvalidFallback($anyValue): void
    {
        $translatable = new TranslatableDateTime();

        $translatable->setFallback($anyValue);
        static::assertNull($translatable->getFallback());
    }
}
