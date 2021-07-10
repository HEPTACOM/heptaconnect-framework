<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableInteger;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\SetStateTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableInteger
 */
class TranslatableIntegerTest extends TestCase
{
    use ProvidesIntegerTestsData;
    use ProvidesInvalidTestsData;
    use ProvidesJsonSerializer;

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testInsertTypeInTypeTranslatable(int $item): void
    {
        $translatable = new TranslatableInteger();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $item);
        static::assertEquals($item, $translatable->getTranslation($localeKey));
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testArrayNotationInsertTypeInTypeTranslatable(int $item): void
    {
        $translatable = new TranslatableInteger();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $item;
        static::assertEquals($item, $translatable[$localeKey]);
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testRemovalViaNullValue(int $anyValue): void
    {
        $translatable = new TranslatableInteger();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->getTranslation($localeKey));
        $translatable->removeTranslation($localeKey);
        static::assertNull($translatable->getTranslation($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testRemovalViaArrayNullAssignment(int $anyValue): void
    {
        $translatable = new TranslatableInteger();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $anyValue;
        static::assertEquals($anyValue, $translatable[$localeKey]);
        $translatable[$localeKey] = null;
        static::assertNull($translatable[$localeKey]);
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testRemovalViaUnset(int $anyValue): void
    {
        $translatable = new TranslatableInteger();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $anyValue;
        static::assertEquals($anyValue, $translatable[$localeKey]);
        static::assertTrue(isset($translatable[$localeKey]));
        unset($translatable[$localeKey]);
        static::assertNull($translatable[$localeKey]);
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testAccessViaOffset(int $anyValue): void
    {
        $translatable = new TranslatableInteger();
        $localeKey = 'en-GB';
        $translatable->offsetSet($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->offsetGet($localeKey));
        static::assertTrue($translatable->offsetExists($localeKey));
        $translatable->offsetUnset($localeKey);
        static::assertNull($translatable->offsetGet($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testChainableCalls(int $anyValue): void
    {
        $translatable = new TranslatableInteger();
        static::assertEquals($translatable, $translatable->setTranslation('en-GB', $anyValue));
        static::assertEquals($translatable, $translatable->removeTranslation('en-GB'));
    }

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testAccessDenialViaNumericKey(int $anyValue): void
    {
        $translatable = new TranslatableInteger();
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
     * @dataProvider provideValidIntegerTestCases
     */
    public function testSetState(int $anyValue): void
    {
        /** @var AbstractTranslatable $translatable */
        $translatable = TranslatableInteger::__set_state([
            'translations' => [
                'en-GB' => $anyValue,
            ],
        ]);
        static::assertCount(1, $translatable->getLocaleKeys());
        static::assertEquals($anyValue, $translatable->getTranslation('en-GB'));
    }

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testInvalidSetStateValues(int $anyValue): void
    {
        /** @var AbstractTranslatable $translatable */
        $translatable = TranslatableInteger::__set_state([
            'translations' => $anyValue,
        ]);
        static::assertCount(0, $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $translatable = new TranslatableInteger();
        $translatable->setTranslation('en-GB', $item);
        static::assertCount(0, $translatable->getLocaleKeys());
    }

    public function testSerialization(): void
    {
        $translatable = new TranslatableInteger();
        $translatable->setTranslation('en-GB', 1234567890);
        static::assertEquals(['en-GB' => 1234567890], $this->jsonEncodeAndDecode($translatable));
    }

    /**
     * @dataProvider provideValidIntegerTestCases
     */
    public function testValidFallback(int $anyValue): void
    {
        $translatable = new TranslatableInteger();

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
        $translatable = new TranslatableInteger();

        $translatable->setFallback($anyValue);
        static::assertNull($translatable->getFallback());
    }
}
