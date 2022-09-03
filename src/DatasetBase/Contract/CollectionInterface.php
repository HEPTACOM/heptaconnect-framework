<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

/**
 * @template T
 * @extends iterable<int, T>
 * @extends \ArrayAccess<int, T>
 * @extends \IteratorAggregate<int, T>
 */
interface CollectionInterface extends \IteratorAggregate, \Countable, \ArrayAccess, \JsonSerializable
{
    /**
     * @psalm-param iterable<int, T> $items
     */
    public function push(iterable $items): void;

    /**
     * @return T|null
     */
    public function pop();

    /**
     * @return T|null
     */
    public function shift();

    public function clear(): void;

    /**
     * Returns true, when no entry is in the collection, otherwise false.
     */
    public function isEmpty(): bool;

    /**
     * @return T|null
     */
    public function first();

    /**
     * @return T|null
     */
    public function last();

    /**
     * @param callable(mixed):bool $filterFn
     *
     * @return static
     */
    public function filter(callable $filterFn): self;

    /**
     * @psalm-param callable(T, array-key):mixed|callable(T):mixed $mapFn
     *
     * @return iterable<int, T>
     */
    public function map(callable $mapFn): iterable;

    public function column(string $valueAccessor, ?string $keyAccessor = null): iterable;

    /**
     * Create a new collection of the same type, but without any content.
     */
    public function withoutItems(): self;

    /**
     * Group items in maximum $size big chunks. The last chunk can be less than $size items.
     *
     * @psalm-param positive-int $size
     * @psalm-return iterable<self&non-empty-list<T>>
     */
    public function chunk(int $size): iterable;

    /**
     * Returns the items as a fixed size array. This is useful to use with methods that don't support iterables.
     *
     * @return array<T>
     */
    public function asArray(): array;

    /**
     * Returns the collection in reversed order.
     *
     * @return static
     */
    public function reverse(): self;

    /**
     * Returns true, when the item is in the collection, otherwise false.
     *
     * @param T $value
     */
    public function contains($value): bool;

    /**
     * Returns a copy of this collection only containing items a single time.
     */
    public function unique(): self;
}
