<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

use Heptacom\HeptaConnect\Dataset\Base\Support\SetStateTrait;
use Heptacom\HeptaConnect\Dataset\Base\Translatable\Contract\TranslatableInterface;

/**
 * @template T
 * @implements \ArrayAccess<array-key, T>
 * @implements Contract\TranslatableInterface<T>
 */
abstract class GenericTranslatable implements \ArrayAccess, \JsonSerializable, Contract\TranslatableInterface
{
    use SetStateTrait;

    /**
     * @psalm-var T[]
     */
    protected array $translations = [];

    public static function __set_state(array $an_array)
    {
        $result = static::createStaticFromArray($an_array);
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
     * @psalm-param array-key $offset
     */
    public function offsetExists($offset): bool
    {
        if (!\is_string($offset)) {
            return false;
        }

        return \array_key_exists($offset, $this->translations);
    }

    /**
     * @psalm-param array-key $offset
     *
     * @psalm-return T|null
     */
    public function offsetGet($offset)
    {
        if (!\is_string($offset)) {
            return null;
        }

        return $this->translations[$offset] ?? null;
    }

    /**
     * @psalm-param array-key|null $offset
     * @psalm-param T|null $value
     */
    public function offsetSet($offset, $value): void
    {
        if (!\is_string($offset)) {
            return;
        }

        if (\is_null($value)) {
            $this->deleteTranslation($offset);
        } elseif ($this->isValidValue($value)) {
            $this->translations[$offset] = $value;
        }
    }

    /**
     * @psalm-param array-key $offset
     */
    public function offsetUnset($offset): void
    {
        if (!\is_string($offset)) {
            return;
        }

        $this->deleteTranslation($offset);
    }

    /**
     * @psalm-return T|null
     */
    public function getTranslation(string $localeKey)
    {
        return $this->translations[$localeKey] ?? null;
    }

    /**
     * @psalm-param T $value
     *
     * @psalm-return TranslatableInterface<T>
     */
    public function setTranslation(string $localeKey, $value): TranslatableInterface
    {
        if (!\is_null($value) && $this->isValidValue($value)) {
            $this->translations[$localeKey] = $value;
        }

        return $this;
    }

    /**
     * @psalm-return TranslatableInterface<T>
     */
    public function removeTranslation(string $localeKey): TranslatableInterface
    {
        $this->deleteTranslation($localeKey);

        return $this;
    }

    public function getLocaleKeys(): array
    {
        /** @var string[] $stringKeys */
        $stringKeys = \array_filter(\array_keys($this->translations), 'is_string');

        return \array_values($stringKeys);
    }

    public function jsonSerialize()
    {
        return $this->translations;
    }

    /**
     * @psalm-param T $value
     */
    abstract protected function isValidValue($value): bool;

    private function deleteTranslation(string $offset): void
    {
        if (\array_key_exists($offset, $this->translations)) {
            unset($this->translations[$offset]);
        }
    }
}
