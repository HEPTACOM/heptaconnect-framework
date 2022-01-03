<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;

/**
 * @template T
 * @template-implements \Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface<T>
 */
abstract class AbstractCollection implements CollectionInterface
{
    use JsonSerializeObjectVarsTrait;
    use SetStateTrait;

    /**
     * @var array<array-key, T>
     */
    protected array $items = [];

    /**
     * @psalm-param iterable<int, T> $items
     */
    public function __construct(iterable $items = [])
    {
        $this->push($items);
    }

    public static function __set_state(array $an_array)
    {
        $result = static::createStaticFromArray($an_array);
        /** @var array|mixed $items */
        $items = $an_array['items'] ?? [];

        if (\is_array($items) && $items !== []) {
            $result->push($items);
        }

        return $result;
    }

    public function push(iterable $items): void
    {
        $items = \iterable_to_array($this->filterValid($items));

        if (\count($items) === 0) {
            return;
        }

        \array_push($this->items, ...$items);
    }

    public function pop()
    {
        return \array_pop($this->items);
    }

    public function shift()
    {
        return \array_shift($this->items);
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
     * @param array-key $offset
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
    public function offsetSet($offset, $value): void
    {
        if ($offset !== null && $this->isValidItem($value)) {
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
        $end = \reset($this->items);

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

    public function map(callable $mapFn): iterable
    {
        yield from \array_map($mapFn, $this->items, \array_keys($this->items));
    }

    public function column(string $valueAccessor, ?string $keyAccessor = null): iterable
    {
        foreach ($this as $key => $value) {
            yield $this->executeAccessor($value, $keyAccessor, $key) => $this->executeAccessor($value, $valueAccessor, $value);
        }
    }

    /**
     * @psalm-param T $item
     */
    abstract protected function isValidItem($item): bool;

    protected function filterValid(iterable $items): \Generator
    {
        /**
         * @var int $key
         * @psalm-var T $item
         */
        foreach ($items as $key => $item) {
            if ($this->isValidItem($item)) {
                yield $key => $item;
            }
        }
    }

    /**
     * @param mixed $item
     * @param mixed $fallback
     *
     * @return mixed
     */
    protected function executeAccessor($item, ?string $accessor, $fallback)
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
}
