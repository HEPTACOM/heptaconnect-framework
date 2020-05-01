<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableDateTime;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\GenericTranslatable
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableDateTime
 */
class TranslatableDateTimeTest extends TestCase
{
    /**
     * @dataProvider provideValidTestCases
     */
    public function testInsertTypeInTypeTranslatable(\DateTimeInterface $item): void
    {
        $translatable = new TranslatableDateTime();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $item);
        $this->assertEquals($item, $translatable->getTranslation($localeKey));
        $this->assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testArrayNotationInsertTypeInTypeTranslatable(\DateTimeInterface $item): void
    {
        $translatable = new TranslatableDateTime();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $item;
        $this->assertEquals($item, $translatable[$localeKey]);
        $this->assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    /**
     * @dataProvider provideValidTestCases
     */
    public function testRemovalViaNullValue(\DateTimeInterface $anyValue): void
    {
        $translatable = new TranslatableDateTime();
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
    public function testRemovalViaArrayNullAssignment(\DateTimeInterface $anyValue): void
    {
        $translatable = new TranslatableDateTime();
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
    public function testRemovalViaUnset(\DateTimeInterface $anyValue): void
    {
        $translatable = new TranslatableDateTime();
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
    public function testAccessViaOffset(\DateTimeInterface $anyValue): void
    {
        $translatable = new TranslatableDateTime();
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
        $translatable = new TranslatableDateTime();
        $this->assertEquals($translatable, $translatable->setTranslation('en-GB', null));
    }

    /**
     * @return iterable<array-key, array<int, \DateTimeInterface>>
     */
    public function provideValidTestCases(): iterable
    {
        yield [\date_create()];
    }
}
