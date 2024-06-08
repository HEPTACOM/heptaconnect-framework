<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableDate;
use Heptacom\HeptaConnect\Utility\Date\Date;
use Heptacom\HeptaConnect\Utility\Php\SetStateTrait;
use Heptacom\HeptaConnect\Utility\Test\ProvidesDateTestsData;
use Heptacom\HeptaConnect\Utility\Test\ProvidesInvalidTestsData;
use Heptacom\HeptaConnect\Utility\Test\ProvidesJsonSerializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractTranslatable::class)]
#[CoversClass(TranslatableDate::class)]
#[CoversClass(Date::class)]
#[CoversTrait(SetStateTrait::class)]
final class TranslatableDateTest extends TestCase
{
    use ProvidesDateTestsData;
    use ProvidesInvalidTestsData;
    use ProvidesJsonSerializer;

    #[DataProvider('provideValidDateTestCases')]
    public function testInsertTypeInTypeTranslatable(Date $item): void
    {
        $translatable = new TranslatableDate();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $item);
        static::assertEquals($item, $translatable->getTranslation($localeKey));
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidDateTestCases')]
    public function testArrayNotationInsertTypeInTypeTranslatable(Date $item): void
    {
        $translatable = new TranslatableDate();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $item;
        static::assertEquals($item, $translatable[$localeKey]);
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidDateTestCases')]
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

    #[DataProvider('provideValidDateTestCases')]
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

    #[DataProvider('provideValidDateTestCases')]
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

    #[DataProvider('provideValidDateTestCases')]
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

    #[DataProvider('provideValidDateTestCases')]
    public function testChainableCalls(Date $anyValue): void
    {
        $translatable = new TranslatableDate();
        static::assertEquals($translatable, $translatable->setTranslation('en-GB', $anyValue));
        static::assertEquals($translatable, $translatable->removeTranslation('en-GB'));
    }

    #[DataProvider('provideValidDateTestCases')]
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

    #[DataProvider('provideValidDateTestCases')]
    public function testSetState(Date $anyValue): void
    {
        /** @var AbstractTranslatable $translatable */
        $translatable = TranslatableDate::__set_state([
            'translations' => [
                'en-GB' => $anyValue,
            ],
        ]);
        static::assertCount(1, $translatable->getLocaleKeys());
        static::assertEquals($anyValue, $translatable->getTranslation('en-GB'));
    }

    #[DataProvider('provideValidDateTestCases')]
    public function testInvalidSetStateValues(Date $anyValue): void
    {
        /** @var AbstractTranslatable $translatable */
        $translatable = TranslatableDate::__set_state([
            'translations' => $anyValue,
        ]);
        static::assertCount(0, $translatable->getLocaleKeys());
    }

    #[DataProvider('provideInvalidTestCases')]
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

    #[DataProvider('provideValidDateTestCases')]
    public function testValidFallback(Date $anyValue): void
    {
        $translatable = new TranslatableDate();

        $translatable->setFallback($anyValue);
        static::assertSame($anyValue, $translatable->getFallback());
        $translatable->removeFallback();
        static::assertNull($translatable->getFallback());
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testInvalidFallback($anyValue): void
    {
        $translatable = new TranslatableDate();

        $translatable->setFallback($anyValue);
        static::assertNull($translatable->getFallback());
    }
}
