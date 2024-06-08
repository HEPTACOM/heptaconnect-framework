<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableDateTime;
use Heptacom\HeptaConnect\Utility\Php\SetStateTrait;
use Heptacom\HeptaConnect\Utility\Test\ProvidesDateTimeTestsData;
use Heptacom\HeptaConnect\Utility\Test\ProvidesInvalidTestsData;
use Heptacom\HeptaConnect\Utility\Test\ProvidesJsonSerializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversTrait(SetStateTrait::class)]
#[CoversClass(AbstractTranslatable::class)]
#[CoversClass(TranslatableDateTime::class)]
final class TranslatableDateTimeTest extends TestCase
{
    use ProvidesDateTimeTestsData;
    use ProvidesInvalidTestsData;
    use ProvidesJsonSerializer;

    #[DataProvider('provideValidDateTimeTestCases')]
    public function testInsertTypeInTypeTranslatable(\DateTimeInterface $item): void
    {
        $translatable = new TranslatableDateTime();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $item);
        static::assertEquals($item, $translatable->getTranslation($localeKey));
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidDateTimeTestCases')]
    public function testArrayNotationInsertTypeInTypeTranslatable(\DateTimeInterface $item): void
    {
        $translatable = new TranslatableDateTime();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $item;
        static::assertEquals($item, $translatable[$localeKey]);
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidDateTimeTestCases')]
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

    #[DataProvider('provideValidDateTimeTestCases')]
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

    #[DataProvider('provideValidDateTimeTestCases')]
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

    #[DataProvider('provideValidDateTimeTestCases')]
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

    #[DataProvider('provideValidDateTimeTestCases')]
    public function testChainableCalls(\DateTimeInterface $anyValue): void
    {
        $translatable = new TranslatableDateTime();
        static::assertEquals($translatable, $translatable->setTranslation('en-GB', $anyValue));
        static::assertEquals($translatable, $translatable->removeTranslation('en-GB'));
    }

    #[DataProvider('provideValidDateTimeTestCases')]
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

    #[DataProvider('provideValidDateTimeTestCases')]
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

    #[DataProvider('provideValidDateTimeTestCases')]
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

    #[DataProvider('provideInvalidTestCases')]
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        static::expectException(\TypeError::class);
        static::expectExceptionMessageMatches('/must be of type \\\\DateTimeInterface/');

        $translatable = new TranslatableDateTime();
        $translatable->setTranslation('en-GB', $item);
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

    #[DataProvider('provideValidDateTimeTestCases')]
    public function testValidFallback(\DateTimeInterface $anyValue): void
    {
        $translatable = new TranslatableDateTime();

        $translatable->setFallback($anyValue);
        static::assertSame($anyValue, $translatable->getFallback());
        $translatable->removeFallback();
        static::assertNull($translatable->getFallback());
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testInvalidFallback($anyValue): void
    {
        static::expectException(\TypeError::class);
        static::expectExceptionMessageMatches('/must be of type \\\\DateTimeInterface/');

        $translatable = new TranslatableDateTime();
        $translatable->setFallback($anyValue);
    }
}
