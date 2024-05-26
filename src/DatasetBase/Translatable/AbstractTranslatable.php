<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

use Heptacom\HeptaConnect\Dataset\Base\Translatable\Contract\TranslatableInterface;
use Heptacom\HeptaConnect\Utility\Php\SetStateTrait;

/**
 * @template T
 *
 * @implements \ArrayAccess<array-key, T>
 * @implements Contract\TranslatableInterface<T>
 */
abstract class AbstractTranslatable implements \ArrayAccess, \JsonSerializable, Contract\TranslatableInterface
{
    use SetStateTrait;

    /**
     * @var T[]
     */
    protected array $translations = [];

    /**
     * @var T|null
     */
    protected mixed $fallback = null;

    public static function __set_state(array $an_array): static
    {
        $result = self::createStaticFromArray($an_array);
        /** @var array|mixed $items */
        $items = $an_array['translations'] ?? [];

        if (\is_array($items) && $items !== []) {
            /** @var T|mixed $value */
            foreach ($items as $localeKey => $value) {
                if (\is_numeric($localeKey)) {
                    continue;
                }

                $result->setTranslation($localeKey, $value);
            }
        }

        return $result;
    }

    /**
     * @param string|int $offset
     */
    #[\Override]
    public function offsetExists($offset): bool
    {
        if (!\is_string($offset)) {
            return false;
        }

        return \array_key_exists($offset, $this->translations);
    }

    /**
     * @param string|int $offset
     *
     * @return T|null
     */
    #[\Override]
    public function offsetGet($offset): mixed
    {
        if (!\is_string($offset)) {
            return null;
        }

        return $this->getTranslation($offset);
    }

    /**
     * @param int|string|null $offset
     * @param T|null $value
     */
    #[\Override]
    public function offsetSet($offset, $value): void
    {
        if (!\is_string($offset)) {
            return;
        }

        if ($value === null) {
            $this->deleteTranslation($offset);
        } elseif ($this->isValidValue($value)) {
            $this->translations[$offset] = $value;
        }
    }

    /**
     * @param int|string $offset
     */
    #[\Override]
    public function offsetUnset($offset): void
    {
        if (!\is_string($offset)) {
            return;
        }

        $this->deleteTranslation($offset);
    }

    /**
     * @return T|null
     */
    #[\Override]
    public function getTranslation(string $localeKey, bool $returnFallback = false): mixed
    {
        /* @deprecated 1.0.0 */
        if ($localeKey === 'default') {
            @\trigger_error('The key "default" for translations is deprecated. Use getFallback instead.', \E_USER_DEPRECATED);
            $this->removeFallback();
        }

        return $this->translations[$localeKey] ?? ($returnFallback ? $this->getFallback() : null);
    }

    /**
     * @param T $value
     *
     * @return TranslatableInterface<T>
     */
    #[\Override]
    public function setTranslation(string $localeKey, $value): TranslatableInterface
    {
        if ($value !== null && $this->isValidValue($value)) {
            $this->translations[$localeKey] = $value;

            /* @deprecated 1.0.0 */
            if ($localeKey === 'default') {
                @\trigger_error('The key "default" for translations is deprecated. Use setFallback instead.', \E_USER_DEPRECATED);
                $this->removeFallback();
            }
        }

        return $this;
    }

    /**
     * @return TranslatableInterface<T>
     */
    #[\Override]
    public function removeTranslation(string $localeKey): TranslatableInterface
    {
        $this->deleteTranslation($localeKey);

        /* @deprecated 1.0.0 */
        if ($localeKey === 'default') {
            @\trigger_error('The key "default" for translations is deprecated. Use removeFallback instead.', \E_USER_DEPRECATED);
            $this->removeFallback();
        }

        return $this;
    }

    /**
     * @return T|null
     */
    #[\Override]
    public function getFallback(): mixed
    {
        return $this->fallback;
    }

    /**
     * @param T $value
     *
     * @return TranslatableInterface<T>
     */
    #[\Override]
    public function setFallback($value): TranslatableInterface
    {
        if ($value === null || $this->isValidValue($value)) {
            $this->fallback = $value;
        }

        return $this;
    }

    /**
     * @return TranslatableInterface<T>
     */
    #[\Override]
    public function removeFallback(): TranslatableInterface
    {
        $this->fallback = null;

        return $this;
    }

    #[\Override]
    public function getLocaleKeys(): array
    {
        /** @var string[] $stringKeys */
        $stringKeys = \array_filter(\array_keys($this->translations), 'is_string');

        return \array_values($stringKeys);
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return $this->translations;
    }

    /**
     * @phpstan-assert-if-true T $value
     */
    abstract protected function isValidValue(mixed $value): bool;

    private function deleteTranslation(string $offset): void
    {
        if (\array_key_exists($offset, $this->translations)) {
            unset($this->translations[$offset]);
        }
    }
}
