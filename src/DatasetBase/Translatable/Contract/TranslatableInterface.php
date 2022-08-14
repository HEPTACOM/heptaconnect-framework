<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable\Contract;

/**
 * Identifies a type that can have different values by locale identifier.
 *
 * @template T
 */
interface TranslatableInterface
{
    /**
     * Looks up a translation by the given locale identifier.
     *
     * @deprecated 1.0.0 Invert returnFallback to true
     *
     * @return T|null
     */
    public function getTranslation(string $localeKey, bool $returnFallback = false);

    /**
     * Sets a translation by the given locale identifier.
     *
     * @psalm-param T $value
     *
     * @param T $value
     *
     * @return TranslatableInterface<T>
     */
    public function setTranslation(string $localeKey, $value): TranslatableInterface;

    /**
     * Removes any translation given by the locale identifier.
     *
     * @return TranslatableInterface<T>
     */
    public function removeTranslation(string $localeKey): TranslatableInterface;

    /**
     * Returns the fallback value, that is used by @see getTranslation
     *
     * @return T|null
     */
    public function getFallback();

    /**
     * Sets the fallback value, that is used by @see getTranslation
     *
     * @psalm-param T $value
     *
     * @param T $value
     *
     * @return TranslatableInterface<T>
     */
    public function setFallback($value): TranslatableInterface;

    /**
     * Empties the fallback values, that is used by @see getTranslation
     *
     * @return TranslatableInterface<T>
     */
    public function removeFallback(): TranslatableInterface;

    /**
     * Returns the list of locale identifiers, that have been set of this object.
     *
     * @return array<string>
     */
    public function getLocaleKeys(): array;
}
