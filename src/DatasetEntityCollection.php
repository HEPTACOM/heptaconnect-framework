<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

/**
 * @template T
 * @implements Contract\DatasetEntityCollectionInterface<T>
 */
abstract class DatasetEntityCollection implements Contract\DatasetEntityCollectionInterface
{
    use Support\JsonSerializeObjectVarsTrait;

    /**
     * @var array<array-key, T>
     */
    protected array $items = [];

    /**
     * @psalm-param array<array-key, T> $items
     */
    public function __construct(array $items = [])
    {
        $this->push($items);
    }

    public function push(array $items): void
    {
        $items = \iterator_to_array($this->filterValid($items));

        if (\count($items) === 0) {
            return;
        }

        \array_push($this->items, ...$items);
    }

    public function clear(): void
    {
        $this->items = [];
    }

    public function count(): int
    {
        return \count($this->items);
    }

    public function jsonSerialize(): array
    {
        return \array_values($this->items);
    }

    public function getIterator()
    {
        yield from $this->items;
    }

    /**
     * @param string|int $offset
     */
    public function offsetExists($offset): bool
    {
        return \array_key_exists($offset, $this->items);
    }

    /**
     * @param string|int $offset
     *
     * @return T|null
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * @param array-key|null $offset
     * @psalm-param T   $value
     */
    public function offsetSet($offset, $value)
    {
        if (!\is_null($offset) && $this->isValidItem($value)) {
            $this->items[$offset] = $value;
        }
    }

    /**
     * @param string|int $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * @return T|null
     */
    public function first()
    {
        $end = \current($this->items);

        return $end === false ? null : $end;
    }

    /**
     * @return T|null
     */
    public function last()
    {
        $end = \end($this->items);

        return $end === false ? null : $end;
    }

    public function filter(callable $filterFn): \Generator
    {
        yield from \array_filter($this->items, $filterFn);
    }

    abstract protected function getT(): string;

    protected function filterValid(iterable $items): \Generator
    {
        /** @var T $item */
        foreach ($items as $key => $item) {
            if ($this->isValidItem($item)) {
                yield $key => $item;
            }
        }
    }

    /**
     * @psalm-param T $item
     */
    protected function isValidItem($item): bool
    {
        $expected = $this->getT();

        return \is_object($item) && $item instanceof $expected;
    }
}
