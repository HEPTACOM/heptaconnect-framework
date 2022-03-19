<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\Contract;

/**
 * @template T
 */
interface TranslatableInterface
{
    /**
     * @deprecated 1.0.0 Invert returnFallback to true
     *
     * @return T|null
     */
    public function getTranslation(string $localeKey, bool $returnFallback = false);

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
     * @return T|null
     */
    public function getFallback();

    /**
     * @psalm-param T $value
     *
     * @param T $value
     *
     * @return TranslatableInterface<T>
     */
    public function setFallback($value): TranslatableInterface;

    /**
     * @return TranslatableInterface<T>
     */
    public function removeFallback(): TranslatableInterface;

    /**
     * @return array<string>
     */
    public function getLocaleKeys(): array;
}
