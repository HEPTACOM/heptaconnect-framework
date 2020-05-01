<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableBoolean;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\GenericTranslatable
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableBoolean
 */
class TranslatableBoolTest extends TestCase
{
    /**
     * @dataProvider provideValidTestCases
     */
    public function testInsertTypeInTypeTranslatable(bool $item): void
    {
        $translatable = new TranslatableBoolean();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $item);
        $this->assertEquals($item, $translatable->getTranslation($localeKey));
        $this->assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testArrayNotationInsertTypeInTypeTranslatable(bool $item): void
    {
        $translatable = new TranslatableBoolean();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $item;
        $this->assertEquals($item, $translatable[$localeKey]);
        $this->assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testRemovalViaNullValue(bool $anyValue): void
    {
        $translatable = new TranslatableBoolean();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $anyValue);
        $this->assertEquals($anyValue, $translatable->getTranslation($localeKey));
        $translatable->setTranslation($localeKey, null);
        $this->assertNull($translatable->getTranslation($localeKey));
        $this->assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testRemovalViaArrayNullAssignment(bool $anyValue): void
    {
        $translatable = new TranslatableBoolean();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $anyValue;
        $this->assertEquals($anyValue, $translatable[$localeKey]);
        $translatable[$localeKey] = null;
        $this->assertNull($translatable[$localeKey]);
        $this->assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testRemovalViaUnset(bool $anyValue): void
    {
        $translatable = new TranslatableBoolean();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $anyValue;
        $this->assertEquals($anyValue, $translatable[$localeKey]);
        $this->assertTrue(isset($translatable[$localeKey]));
        unset($translatable[$localeKey]);
        $this->assertNull($translatable[$localeKey]);
        $this->assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testAccessViaOffset(bool $anyValue): void
    {
        $translatable = new TranslatableBoolean();
        $localeKey = 'en-GB';
        $translatable->offsetSet($localeKey, $anyValue);
        $this->assertEquals($anyValue, $translatable->offsetGet($localeKey));
        $this->assertTrue($translatable->offsetExists($localeKey));
        $translatable->offsetUnset($localeKey);
        $this->assertNull($translatable->offsetGet($localeKey));
        $this->assertEmpty($translatable->getLocaleKeys());
    }

    /**
     * @return iterable<array-key, array<int, bool>>
     */
    public function provideValidTestCases(): iterable
    {
        yield [true];
        yield [false];
    }
}
