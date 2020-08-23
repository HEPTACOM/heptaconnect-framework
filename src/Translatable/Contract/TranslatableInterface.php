<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\Contract;

/**
 * @template T
 */
interface TranslatableInterface
{
    /**
     * @return T|null
     */
    public function getTranslation(string $localeKey);

    /**
     * @psalm-param T $value
     *
     * @param T $value
     *
     * @return TranslatableInterface<T>
     */
    public function setTranslation(string $localeKey, $value): TranslatableInterface;

    /**
     * @return TranslatableInterface<T>
     */
    public function removeTranslation(string $localeKey): TranslatableInterface;

    /**
     * @return array<string>
     */
    public function getLocaleKeys(): array;
}
