<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection;

use Heptacom\HeptaConnect\Utility\Collection\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Utility\Php\SetStateTrait;

/**
 * @template T
 *
 * @template-implements CollectionInterface<T>
 */
abstract class AbstractCollection implements CollectionInterface
{
    /**
     * @use IterableImplementationTrait<T>
     */
    use IterableImplementationTrait;
    use SetStateTrait;

    /**
     * @var array<int, T>
     */
    protected array $items = [];

    /**
     * Make sure to override @see recreateWithNewItems when changing the constructor signature.
     *
     * @param iterable<T> $items
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(iterable $items = [])
    {
        $this->push($items);
    }

    public static function __set_state(array $an_array): static
    {
        $result = self::createStaticFromArray($an_array);
        /** @var array|mixed $items */
        $items = $an_array['items'] ?? [];

        if (\is_array($items) && $items !== []) {
            $result->items = $items;
        }

        return $result;
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return \array_values($this->items);
    }

    #[\Override]
    public function push(iterable $items): void
    {
        $newItems = [];

        foreach ($this->validateItems($items) as $item) {
            $newItems[] = $item;
        }

        if (\count($newItems) === 0) {
            return;
        }

        \array_push($this->items, ...$newItems);
    }

    #[\Override]
    public function pushIgnoreInvalidItems(iterable $items): void
    {
        $this->push($this->filterValid($items));
    }

    #[\Override]
    public function pop()
    {
        return \array_pop($this->items);
    }

    #[\Override]
    public function shift()
    {
        return \array_shift($this->items);
    }

    #[\Override]
    public function clear(): void
    {
        $this->items = [];
    }

    /**
     * @phpstan-param array-key|null $offset
     * @phpstan-param T   $value
     */
    #[\Override]
    public function offsetSet($offset, $value): void
    {
        if (\is_numeric($offset) && $this->isValidItem($value)) {
            $this->items[(int) $offset] = $value;
        }

        if ($offset === null) {
            $this->push([$value]);
        }
    }

    /**
     * @param string|int $offset
     */
    #[\Override]
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    #[\Override]
    public function reverse(): void
    {
        $this->items = \array_reverse($this->items);
    }

    #[\Override]
    public function asUnique(): static
    {
        $result = $this->withoutItems();

        foreach ($this->items as $item) {
            if (!$result->contains($item)) {
                $result->push([$item]);
            }
        }

        return $result;
    }

    #[\Override]
    public function withoutItems(): static
    {
        $that = clone $this;

        $that->clear();

        return $that;
    }

    protected function recreateWithNewItems(iterable $items): static
    {
        $result = $this->withoutItems();

        $result->push($items);

        return $result;
    }
}
