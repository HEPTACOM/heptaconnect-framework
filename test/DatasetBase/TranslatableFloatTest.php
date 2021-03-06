<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableFloat;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\SetStateTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableFloat
 */
final class TranslatableFloatTest extends TestCase
{
    use ProvidesFloatTestsData;
    use ProvidesInvalidTestsData;
    use ProvidesJsonSerializer;

    /**
     * @dataProvider provideValidFloatTestCases
     */
    public function testInsertTypeInTypeTranslatable(float $item): void
    {
        $translatable = new TranslatableFloat();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $item);
        static::assertEquals($item, $translatable->getTranslation($localeKey));
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidFloatTestCases
     */
    public function testArrayNotationInsertTypeInTypeTranslatable(float $item): void
    {
        $translatable = new TranslatableFloat();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $item;
        static::assertEquals($item, $translatable[$localeKey]);
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidFloatTestCases
     */
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

    /**
     * @dataProvider provideValidFloatTestCases
     */
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

    /**
     * @dataProvider provideValidFloatTestCases
     */
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

    /**
     * @dataProvider provideValidFloatTestCases
     */
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

    /**
     * @dataProvider provideValidFloatTestCases
     */
    public function testChainableCalls(float $anyValue): void
    {
        $translatable = new TranslatableFloat();
        static::assertEquals($translatable, $translatable->setTranslation('en-GB', $anyValue));
        static::assertEquals($translatable, $translatable->removeTranslation('en-GB'));
    }

    /**
     * @dataProvider provideValidFloatTestCases
     */
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

    /**
     * @dataProvider provideValidFloatTestCases
     */
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

    /**
     * @dataProvider provideValidFloatTestCases
     */
    public function testInvalidSetStateValues(float $anyValue): void
    {
        /** @var AbstractTranslatable $translatable */
        $translatable = TranslatableFloat::__set_state([
            'translations' => $anyValue,
        ]);
        static::assertCount(0, $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
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

    /**
     * @dataProvider provideValidFloatTestCases
     */
    public function testValidFallback(float $anyValue): void
    {
        $translatable = new TranslatableFloat();

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
        $translatable = new TranslatableFloat();

        $translatable->setFallback($anyValue);
        static::assertNull($translatable->getFallback());
    }
}
