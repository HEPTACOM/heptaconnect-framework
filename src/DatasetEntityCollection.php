<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

/**
 * @template T
 */
abstract class DatasetEntityCollection extends DatasetEntity implements Contract\DatasetEntityCollectionInterface
{
    protected array $items = [];

    /**
     * @psalm-param T|object ...$items
     */
    public function __construct(...$items)
    {
        $this->push(...$items);
    }

    public function push(...$items): void
    {
        $items = \iterator_to_array($this->filterValid($items));

        if (empty($items)) {
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

    public function getIterator(): \Generator
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
     * @param string|int $offset
     * @psalm-param object|T   $value
     */
    public function offsetSet($offset, $value)
    {
        if ($this->isValidItem($value)) {
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
        return \reset($this->items) ?: null;
    }

    /**
     * @return T|null
     */
    public function last()
    {
        return \end($this->items) ?: null;
    }

    abstract protected function getT(): string;

    protected function filterValid(iterable $items): \Generator
    {
        /**
         * @var string|int $property
         * @var object|T   $item
         */
        foreach ($items as $key => $item) {
            if ($this->isValidItem($item)) {
                yield $key => $item;
            }
        }
    }

    /**
     * @psalm-param object|T $item
     */
    protected function isValidItem(object $item): bool
    {
        $expected = $this->getT();

        return $item instanceof $expected;
    }
}
