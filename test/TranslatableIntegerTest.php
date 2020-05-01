<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableInteger;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\GenericTranslatable
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableInteger
 */
class TranslatableIntegerTest extends TestCase
{
    /**
     * @dataProvider provideValidTestCases
     */
    public function testInsertTypeInTypeTranslatable(int $item): void
    {
        $translatable = new TranslatableInteger();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $item);
        $this->assertEquals($item, $translatable->getTranslation($localeKey));
        $this->assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testArrayNotationInsertTypeInTypeTranslatable(int $item): void
    {
        $translatable = new TranslatableInteger();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $item;
        $this->assertEquals($item, $translatable[$localeKey]);
        $this->assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testRemovalViaNullValue(int $anyValue): void
    {
        $translatable = new TranslatableInteger();
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
    public function testRemovalViaArrayNullAssignment(int $anyValue): void
    {
        $translatable = new TranslatableInteger();
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
    public function testRemovalViaUnset(int $anyValue): void
    {
        $translatable = new TranslatableInteger();
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
    public function testAccessViaOffset(int $anyValue): void
    {
        $translatable = new TranslatableInteger();
        $localeKey = 'en-GB';
        $translatable->offsetSet($localeKey, $anyValue);
        $this->assertEquals($anyValue, $translatable->offsetGet($localeKey));
        $this->assertTrue($translatable->offsetExists($localeKey));
        $translatable->offsetUnset($localeKey);
        $this->assertNull($translatable->offsetGet($localeKey));
        $this->assertEmpty($translatable->getLocaleKeys());
    }

    public function testChainableCalls(): void
    {
        $translatable = new TranslatableInteger();
        $this->assertEquals($translatable, $translatable->setTranslation('en-GB', null));
    }

    /**
     * @return iterable<array-key, array<int, int>>
     */
    public function provideValidTestCases(): iterable
    {
        yield [0];
        yield [1];
        yield [2];
        yield [3];
        yield [-1000];
        yield [(int) \PHP_INT_MAX];
        yield [(int) \PHP_INT_MIN];
    }
}
