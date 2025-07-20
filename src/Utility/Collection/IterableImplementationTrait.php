<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection;

use Heptacom\HeptaConnect\Utility\Collection\Contract\IterableInterface;

/**
 * This is a shared implementation for AbstractCollection and AbstractIterable.
 * This way we can share code for a readonly and a non-readonly class
 *
 * @template T
 * @phpstan-require-implements IterableInterface<T>
 * @property array<int, T> $items
 */
trait IterableImplementationTrait
{
    public function withItems(iterable $items): static
    {
        return new static($items);
    }

    public function withAddedItems(iterable $items): static
    {
        return $this->withItems([...$this->items, ...$items]);
    }

    #[\Override]
    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    #[\Override]
    public function count(): int
    {
        return \count($this->items);
    }

    #[\ReturnTypeWillChange]
    #[\Override]
    public function getIterator()
    {
        yield from $this->items;
    }

    /**
     * @param string|int $offset
     */
    #[\Override]
    public function offsetExists($offset): bool
    {
        return \array_key_exists($offset, $this->items);
    }

    /**
     * @param array-key $offset
     *
     * @return T|null
     */
    #[\Override]
    public function offsetGet($offset): mixed
    {
        if (!\is_numeric($offset)) {
            throw new \InvalidArgumentException();
        }

        return $this->items[(int) $offset] ?? null;
    }

    /**
     * @return T|null
     */
    #[\Override]
    public function first()
    {
        $end = \reset($this->items);

        return $end === false ? null : $end;
    }

    /**
     * @return T|null
     */
    #[\Override]
    public function last()
    {
        $end = \end($this->items);

        return $end === false ? null : $end;
    }

    #[\Override]
    public function filter(callable $filterFn): static
    {
        return $this->withItems(\array_values(\array_filter($this->items, $filterFn)));
    }

    #[\Override]
    public function map(callable $mapFn): iterable
    {
        yield from \array_map($mapFn, $this->items, \array_keys($this->items));
    }

    #[\Override]
    public function column(?string $valueAccessor, ?string $keyAccessor = null): iterable
    {
        foreach ($this as $key => $value) {
            yield $this->executeAccessor($value, $keyAccessor, $key) => $this->executeAccessor($value, $valueAccessor, $value);
        }
    }

    #[\Override]
    public function chunk(int $size): iterable
    {
        $size = \max($size, 1);
        $buffer = [];
        $chunkIndex = 0;

        foreach ($this as $item) {
            $buffer[$chunkIndex++] = $item;

            if (($chunkIndex % $size) === 0) {
                yield $this->withItems(\array_values($buffer));
                $buffer = [];
            }
        }

        if ($buffer !== []) {
            yield $this->withItems(\array_values($buffer));
        }
    }

    /**
     * @return array<T>
     */
    #[\Override]
    public function asArray(): array
    {
        return $this->items;
    }

    #[\Override]
    public function contains($value): bool
    {
        return \in_array($value, $this->items, true);
    }

    #[\Override]
    public function asUnique(): static
    {
        $result = $this->withItems([]);

        foreach ($this->items as $item) {
            if (!$result->contains($item)) {
                $result = $result->withAddedItems([$item]);
            }
        }

        return $result;
    }

    /**
     * @phpstan-assert-if-true T $item
     */
    abstract protected function isValidItem(mixed $item): bool;

    /**
     * @return iterable<int, T>
     */
    protected function filterValid(iterable $items): iterable
    {
        foreach ($items as $item) {
            if ($this->isValidItem($item)) {
                yield $item;
            }
        }
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return iterable<T>
     */
    protected function validateItems(iterable $items): iterable
    {
        foreach ($items as $item) {
            if (!$this->isValidItem($item)) {
                throw new \InvalidArgumentException();
            }

            yield $item;
        }
    }

    protected function executeAccessor(mixed $item, ?string $accessor, mixed $fallback): mixed
    {
        if (!\is_string($accessor)) {
            return $fallback;
        }

        if (\is_object($item)) {
            if (\method_exists($item, $accessor)) {
                return $item->$accessor();
            }

            if (\property_exists($item, $accessor)) {
                return $item->$accessor;
            }

            return $fallback;
        }

        if (\is_array($item)) {
            return $item[$accessor] ?? $fallback;
        }

        return $fallback;
    }

    /**
     * Alternative implementation for @see contains to check contains by more detailed object comparision.
     * This is useful, when the collection contains items that can be equal even if they are not identical.
     *
     * @param T $value
     * @param \Closure(T $a,    T $b): bool $equalsCondition
     */
    final protected function containsByEqualsCheck(mixed $value, \Closure $equalsCondition): bool
    {
        if (!$this->isValidItem($value)) {
            return false;
        }

        foreach ($this->items as $item) {
            if ($equalsCondition($item, $value)) {
                return true;
            }
        }

        return false;
    }
}
