<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableFloat;
use Heptacom\HeptaConnect\Utility\Php\SetStateTrait;
use Heptacom\HeptaConnect\Utility\Test\ProvidesFloatTestsData;
use Heptacom\HeptaConnect\Utility\Test\ProvidesInvalidTestsData;
use Heptacom\HeptaConnect\Utility\Test\ProvidesJsonSerializer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractTranslatable::class)]
#[CoversClass(TranslatableFloat::class)]
#[CoversClass(SetStateTrait::class)]
final class TranslatableFloatTest extends TestCase
{
    use ProvidesFloatTestsData;
    use ProvidesInvalidTestsData;
    use ProvidesJsonSerializer;

    #[DataProvider('provideValidFloatTestCases')]
    public function testInsertTypeInTypeTranslatable(float $item): void
    {
        $translatable = new TranslatableFloat();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $item);
        static::assertEquals($item, $translatable->getTranslation($localeKey));
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidFloatTestCases')]
    public function testArrayNotationInsertTypeInTypeTranslatable(float $item): void
    {
        $translatable = new TranslatableFloat();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $item;
        static::assertEquals($item, $translatable[$localeKey]);
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidFloatTestCases')]
    public function testRemovalViaNullValue(float $anyValue): void
    {
        $translatable = new TranslatableFloat();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->getTranslation($localeKey));
        $translatable->removeTranslation($localeKey);
        static::assertNull($translatable->getTranslation($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidFloatTestCases')]
    public function testRemovalViaArrayNullAssignment(float $anyValue): void
    {
        $translatable = new TranslatableFloat();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $anyValue;
        static::assertEquals($anyValue, $translatable[$localeKey]);
        $translatable[$localeKey] = null;
        static::assertNull($translatable[$localeKey]);
        static::assertEmpty($translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidFloatTestCases')]
    public function testRemovalViaUnset(float $anyValue): void
    {
        $translatable = new TranslatableFloat();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $anyValue;
        static::assertEquals($anyValue, $translatable[$localeKey]);
        static::assertTrue(isset($translatable[$localeKey]));
        unset($translatable[$localeKey]);
        static::assertNull($translatable[$localeKey]);
        static::assertEmpty($translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidFloatTestCases')]
    public function testAccessViaOffset(float $anyValue): void
    {
        $translatable = new TranslatableFloat();
        $localeKey = 'en-GB';
        $translatable->offsetSet($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->offsetGet($localeKey));
        static::assertTrue($translatable->offsetExists($localeKey));
        $translatable->offsetUnset($localeKey);
        static::assertNull($translatable->offsetGet($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidFloatTestCases')]
    public function testChainableCalls(float $anyValue): void
    {
        $translatable = new TranslatableFloat();
        static::assertEquals($translatable, $translatable->setTranslation('en-GB', $anyValue));
        static::assertEquals($translatable, $translatable->removeTranslation('en-GB'));
    }

    #[DataProvider('provideValidFloatTestCases')]
    public function testAccessDenialViaNumericKey(float $anyValue): void
    {
        $translatable = new TranslatableFloat();
        $localeKey = '1';
        $translatable->offsetSet($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->offsetGet($localeKey));
        static::assertTrue($translatable->offsetExists($localeKey));
        static::assertFalse($translatable->offsetExists(1));
        $translatable->offsetUnset($localeKey);
        static::assertNull($translatable->offsetGet($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidFloatTestCases')]
    public function testSetState(float $anyValue): void
    {
        /** @var AbstractTranslatable $translatable */
        $translatable = TranslatableFloat::__set_state([
            'translations' => [
                'en-GB' => $anyValue,
            ],
        ]);
        static::assertCount(1, $translatable->getLocaleKeys());
        static::assertEquals($anyValue, $translatable->getTranslation('en-GB'));
    }

    #[DataProvider('provideValidFloatTestCases')]
    public function testInvalidSetStateValues(float $anyValue): void
    {
        /** @var AbstractTranslatable $translatable */
        $translatable = TranslatableFloat::__set_state([
            'translations' => $anyValue,
        ]);
        static::assertCount(0, $translatable->getLocaleKeys());
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $translatable = new TranslatableFloat();
        $translatable->setTranslation('en-GB', $item);
        static::assertCount(0, $translatable->getLocaleKeys());
    }

    public function testSerialization(): void
    {
        $translatable = new TranslatableFloat();
        $translatable->setTranslation('en-GB', 3.142);
        static::assertEquals(['en-GB' => 3.142], $this->jsonEncodeAndDecode($translatable));
    }

    #[DataProvider('provideValidFloatTestCases')]
    public function testValidFallback(float $anyValue): void
    {
        $translatable = new TranslatableFloat();

        $translatable->setFallback($anyValue);
        static::assertSame($anyValue, $translatable->getFallback());
        $translatable->removeFallback();
        static::assertNull($translatable->getFallback());
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testInvalidFallback($anyValue): void
    {
        $translatable = new TranslatableFloat();

        $translatable->setFallback($anyValue);
        static::assertNull($translatable->getFallback());
    }
}
