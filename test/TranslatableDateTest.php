<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Date;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableDate;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\GenericTranslatable
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableDate
 */
class TranslatableDateTest extends TestCase
{
    use ProvidesDateTestsData;
    use ProvidesInvalidTestsData;
    use ProvidesJsonSerializer;

    /**
     * @dataProvider provideValidDateTestCases
     */
    public function testInsertTypeInTypeTranslatable(Date $item): void
    {
        $translatable = new TranslatableDate();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $item);
        static::assertEquals($item, $translatable->getTranslation($localeKey));
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidDateTestCases
     */
    public function testArrayNotationInsertTypeInTypeTranslatable(Date $item): void
    {
        $translatable = new TranslatableDate();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $item;
        static::assertEquals($item, $translatable[$localeKey]);
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidDateTestCases
     */
    public function testRemovalViaNullValue(Date $anyValue): void
    {
        $translatable = new TranslatableDate();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->getTranslation($localeKey));
        $translatable->removeTranslation($localeKey);
        static::assertNull($translatable->getTranslation($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidDateTestCases
     */
    public function testRemovalViaArrayNullAssignment(Date $anyValue): void
    {
        $translatable = new TranslatableDate();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $anyValue;
        static::assertEquals($anyValue, $translatable[$localeKey]);
        $translatable[$localeKey] = null;
        static::assertNull($translatable[$localeKey]);
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidDateTestCases
     */
    public function testRemovalViaUnset(Date $anyValue): void
    {
        $translatable = new TranslatableDate();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $anyValue;
        static::assertEquals($anyValue, $translatable[$localeKey]);
        static::assertTrue(isset($translatable[$localeKey]));
        unset($translatable[$localeKey]);
        static::assertNull($translatable[$localeKey]);
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidDateTestCases
     */
    public function testAccessViaOffset(Date $anyValue): void
    {
        $translatable = new TranslatableDate();
        $localeKey = 'en-GB';
        $translatable->offsetSet($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->offsetGet($localeKey));
        static::assertTrue($translatable->offsetExists($localeKey));
        $translatable->offsetUnset($localeKey);
        static::assertNull($translatable->offsetGet($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidDateTestCases
     */
    public function testChainableCalls(Date $anyValue): void
    {
        $translatable = new TranslatableDate();
        static::assertEquals($translatable, $translatable->setTranslation('en-GB', $anyValue));
        static::assertEquals($translatable, $translatable->removeTranslation('en-GB'));
    }

    /**
     * @dataProvider provideValidDateTestCases
     */
    public function testAccessDenialViaNumericKey(Date $anyValue): void
    {
        $translatable = new TranslatableDate();
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
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $translatable = new TranslatableDate();
        $translatable->setTranslation('en-GB', $item);
        static::assertCount(0, $translatable->getLocaleKeys());
    }

    public function testSerialization(): void
    {
        $translatable = new TranslatableDate();
        $translatable->setTranslation('en-GB', new Date('2010-11-20T14:30:50.000Z', new \DateTimeZone('UTC')));
        static::assertEquals(['en-GB' => [
            'date' => '2010-11-20 00:00:00.000000',
            'timezone_type' => \DateTimeZone::AMERICA,
            'timezone' => 'Z',
        ]], $this->jsonEncodeAndDecode($translatable));
    }
}
