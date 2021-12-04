<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableBoolean;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\SetStateTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableBoolean
 */
class TranslatableBoolTest extends TestCase
{
    use ProvidesBooleanTestsData;
    use ProvidesInvalidTestsData;
    use ProvidesJsonSerializer;

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testInsertTypeInTypeTranslatable(bool $item): void
    {
        $translatable = new TranslatableBoolean();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $item);
        static::assertEquals($item, $translatable->getTranslation($localeKey));
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testArrayNotationInsertTypeInTypeTranslatable(bool $item): void
    {
        $translatable = new TranslatableBoolean();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $item;
        static::assertEquals($item, $translatable[$localeKey]);
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testRemovalViaNullValue(bool $anyValue): void
    {
        $translatable = new TranslatableBoolean();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->getTranslation($localeKey));
        $translatable->removeTranslation($localeKey);
        static::assertNull($translatable->getTranslation($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testRemovalViaArrayNullAssignment(bool $anyValue): void
    {
        $translatable = new TranslatableBoolean();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $anyValue;
        static::assertEquals($anyValue, $translatable[$localeKey]);
        $translatable[$localeKey] = null;
        static::assertNull($translatable[$localeKey]);
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testRemovalViaUnset(bool $anyValue): void
    {
        $translatable = new TranslatableBoolean();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $anyValue;
        static::assertEquals($anyValue, $translatable[$localeKey]);
        static::assertTrue(isset($translatable[$localeKey]));
        unset($translatable[$localeKey]);
        static::assertNull($translatable[$localeKey]);
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testAccessViaOffset(bool $anyValue): void
    {
        $translatable = new TranslatableBoolean();
        $localeKey = 'en-GB';
        $translatable->offsetSet($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->offsetGet($localeKey));
        static::assertTrue($translatable->offsetExists($localeKey));
        $translatable->offsetUnset($localeKey);
        static::assertNull($translatable->offsetGet($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testAccessDenialViaNumericKey(bool $anyValue): void
    {
        $translatable = new TranslatableBoolean();
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
     * @dataProvider provideValidBooleanTestCases
     */
    public function testSetState(bool $anyValue): void
    {
        /** @var AbstractTranslatable $translatable */
        $translatable = TranslatableBoolean::__set_state([
            'translations' => [
                'en-GB' => $anyValue,
            ],
        ]);
        static::assertCount(1, $translatable->getLocaleKeys());
        static::assertEquals($anyValue, $translatable->getTranslation('en-GB'));
    }

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testInvalidSetStateValues(bool $anyValue): void
    {
        /** @var AbstractTranslatable $translatable */
        $translatable = TranslatableBoolean::__set_state([
            'translations' => $anyValue,
        ]);
        static::assertCount(0, $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $translatable = new TranslatableBoolean();
        $translatable->setTranslation('en-GB', $item);
        static::assertCount(0, $translatable->getLocaleKeys());
    }

    public function testSerialization(): void
    {
        $translatable = new TranslatableBoolean();
        $translatable->setTranslation('en-GB', true);
        static::assertEquals(['en-GB' => true], $this->jsonEncodeAndDecode($translatable));
    }

    /**
     * @dataProvider provideValidBooleanTestCases
     */
    public function testValidFallback(bool $anyValue): void
    {
        $translatable = new TranslatableBoolean();

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
        $translatable = new TranslatableBoolean();

        $translatable->setFallback($anyValue);
        static::assertNull($translatable->getFallback());
    }
}
