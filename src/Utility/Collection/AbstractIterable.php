<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection;

use Heptacom\HeptaConnect\Utility\Collection\Contract\IterableInterface;

/**
 * @template T
 *
 * @template-implements IterableInterface<T>
 */
abstract readonly class AbstractIterable implements IterableInterface
{
    /**
     * @use IterableImplementationTrait<T>
     */
    use IterableImplementationTrait;

    /**
     * @var array<int, T>
     */
    protected array $items;

    /**
     * Make sure to override @see withItems when changing the constructor signature.
     *
     * @param iterable<T> $items
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(iterable $items = [])
    {
        $this->items = [...$this->validateItems($items)];
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return $this->items;
    }

    /**
     * @param array-key|null $offset
     * @param T   $value
     */
    #[\Override]
    public function offsetSet($offset, $value): never
    {
        throw new \BadMethodCallException(__METHOD__ . ' is not implemented as the iterable is readonly');
    }

    /**
     * @param string|int $offset
     */
    #[\Override]
    public function offsetUnset($offset): never
    {
        throw new \BadMethodCallException(__METHOD__ . ' is not implemented as the iterable is readonly');
    }
}
