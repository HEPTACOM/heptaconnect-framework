<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

/**
 * @template T
 */
abstract class GenericTranslatable implements \ArrayAccess, Contract\TranslatableInterface
{
    protected array $translations = [];

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
     * @return T|null
     */
    public function offsetGet($offset)
    {
        if (!\is_string($offset)) {
            return null;
        }

        return $this->translations[$offset] ?? null;
    }

    /**
     * @psalm-param array-key $offset
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
     * @return T|null
     */
    public function getTranslation(string $localeKey)
    {
        return $this->translations[$localeKey] ?? null;
    }

    /**
     * @psalm-param T|null $value
     */
    public function setTranslation(string $localeKey, $value): self
    {
        if (\is_null($value)) {
            $this->deleteTranslation($localeKey);
        } elseif ($this->isValidValue($value)) {
            $this->translations[$localeKey] = $value;
        }

        return $this;
    }

    public function getLocaleKeys(): array
    {
        return \array_filter(\array_keys($this->translations), 'is_string');
    }

    /**
     * @psalm-param T|null $value
     */
    abstract protected function isValidValue($value): bool;

    protected function deleteTranslation(string $offset): void
    {
        if (\array_key_exists($offset, $this->translations)) {
            unset($this->translations[$offset]);
        }
    }
}
