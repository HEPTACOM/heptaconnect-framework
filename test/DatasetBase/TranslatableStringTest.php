<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\AbstractTranslatable;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\TranslatableString;
use Heptacom\HeptaConnect\Utility\Php\SetStateTrait;
use Heptacom\HeptaConnect\Utility\Test\ProvidesInvalidTestsData;
use Heptacom\HeptaConnect\Utility\Test\ProvidesJsonSerializer;
use Heptacom\HeptaConnect\Utility\Test\ProvidesStringTestsData;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(AbstractTranslatable::class)]
#[CoversClass(TranslatableString::class)]
#[CoversTrait(SetStateTrait::class)]
final class TranslatableStringTest extends TestCase
{
    use ProvidesInvalidTestsData;
    use ProvidesJsonSerializer;
    use ProvidesStringTestsData;

    #[DataProvider('provideValidStringTestCases')]
    public function testInsertTypeInTypeTranslatable(string $item): void
    {
        $translatable = new TranslatableString();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $item);
        static::assertEquals($item, $translatable->getTranslation($localeKey));
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidStringTestCases')]
    public function testArrayNotationInsertTypeInTypeTranslatable(string $item): void
    {
        $translatable = new TranslatableString();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $item;
        static::assertEquals($item, $translatable[$localeKey]);
        static::assertEquals(['en-GB'], $translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidStringTestCases')]
    public function testRemovalViaNullValue(string $anyValue): void
    {
        $translatable = new TranslatableString();
        $localeKey = 'en-GB';
        $translatable->setTranslation($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->getTranslation($localeKey));
        $translatable->removeTranslation($localeKey);
        static::assertNull($translatable->getTranslation($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidStringTestCases')]
    public function testRemovalViaArrayNullAssignment(string $anyValue): void
    {
        $translatable = new TranslatableString();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $anyValue;
        static::assertEquals($anyValue, $translatable[$localeKey]);
        $translatable[$localeKey] = null;
        static::assertNull($translatable[$localeKey]);
        static::assertEmpty($translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidStringTestCases')]
    public function testRemovalViaUnset(string $anyValue): void
    {
        $translatable = new TranslatableString();
        $localeKey = 'en-GB';
        $translatable[$localeKey] = $anyValue;
        static::assertEquals($anyValue, $translatable[$localeKey]);
        static::assertTrue(isset($translatable[$localeKey]));
        unset($translatable[$localeKey]);
        static::assertNull($translatable[$localeKey]);
        static::assertEmpty($translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidStringTestCases')]
    public function testAccessViaOffset(string $anyValue): void
    {
        $translatable = new TranslatableString();
        $localeKey = 'en-GB';
        $translatable->offsetSet($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->offsetGet($localeKey));
        static::assertTrue($translatable->offsetExists($localeKey));
        $translatable->offsetUnset($localeKey);
        static::assertNull($translatable->offsetGet($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidStringTestCases')]
    public function testChainableCalls(string $anyValue): void
    {
        $translatable = new TranslatableString();
        static::assertEquals($translatable, $translatable->setTranslation('en-GB', $anyValue));
        static::assertEquals($translatable, $translatable->removeTranslation('en-GB'));
    }

    #[DataProvider('provideValidStringTestCases')]
    public function testAccessDenialViaNumericKey(string $anyValue): void
    {
        $translatable = new TranslatableString();
        $localeKey = '1';
        $translatable->offsetSet($localeKey, $anyValue);
        static::assertEquals($anyValue, $translatable->offsetGet($localeKey));
        static::assertTrue($translatable->offsetExists($localeKey));
        static::assertFalse($translatable->offsetExists(1));
        $translatable->offsetUnset($localeKey);
        static::assertNull($translatable->offsetGet($localeKey));
        static::assertEmpty($translatable->getLocaleKeys());
    }

    #[DataProvider('provideValidStringTestCases')]
    public function testSetState(string $anyValue): void
    {
        /** @var AbstractTranslatable $translatable */
        $translatable = TranslatableString::__set_state([
            'translations' => [
                'en-GB' => $anyValue,
            ],
        ]);
        static::assertCount(1, $translatable->getLocaleKeys());
        static::assertEquals($anyValue, $translatable->getTranslation('en-GB'));
    }

    #[DataProvider('provideValidStringTestCases')]
    public function testInvalidSetStateValues(string $anyValue): void
    {
        /** @var AbstractTranslatable $translatable */
        $translatable = TranslatableString::__set_state([
            'translations' => $anyValue,
        ]);
        static::assertCount(0, $translatable->getLocaleKeys());
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $translatable = new TranslatableString();
        $translatable->setTranslation('en-GB', $item);
        static::assertCount(0, $translatable->getLocaleKeys());
    }

    public function testSerialization(): void
    {
        $translatable = new TranslatableString();
        $translatable->setTranslation('en-GB', 'What is this kind of text?');
        static::assertEquals(['en-GB' => 'What is this kind of text?'], $this->jsonEncodeAndDecode($translatable));
    }

    #[DataProvider('provideValidStringTestCases')]
    public function testValidFallback(string $anyValue): void
    {
        $translatable = new TranslatableString();

        $translatable->setFallback($anyValue);
        static::assertSame($anyValue, $translatable->getFallback());
        $translatable->removeFallback();
        static::assertNull($translatable->getFallback());
    }

    #[DataProvider('provideInvalidTestCases')]
    public function testInvalidFallback($anyValue): void
    {
        $translatable = new TranslatableString();

        $translatable->setFallback($anyValue);
        static::assertNull($translatable->getFallback());
    }
}
