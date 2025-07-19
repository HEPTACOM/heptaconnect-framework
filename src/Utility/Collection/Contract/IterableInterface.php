<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection\Contract;

/**
 * Describes an immutable collection resembling classes.
 *
 * @template T
 *
 * @extends \ArrayAccess<int, T>
 * @extends \IteratorAggregate<int, T>
 */
interface IterableInterface extends \IteratorAggregate, \Countable, \ArrayAccess, \JsonSerializable
{
    /**
     * Create a new collection with the given items, which should already part of the original collection.
     *
     * @param iterable<int, T> $items
     */
    public function withItems(iterable $items): static;

    /**
     * Create a new collection with the given items in addition to the already existing items.
     *
     * @param iterable<int, T> $items
     */
    public function withAddedItems(iterable $items): static;

    /**
     * Returns true, when no entry is in the collection, otherwise false.
     */
    public function isEmpty(): bool;

    /**
     * Returns the first element of the collection.
     * When the collection is empty, null is returned.
     *
     * @return T|null
     */
    public function first();

    /**
     * Returns the last element of the collection.
     * When the collection is empty, null is returned.
     *
     * @return T|null
     */
    public function last();

    /**
     * Returns an iterable list of items, that are checked by the given callable.
     *
     * @param callable(mixed):bool $filterFn
     */
    public function filter(callable $filterFn): static;

    /**
     * Returns an iterable list of anything, that is returned for each item by the given callable.
     *
     * @template TMapResult
     *
     * @phpstan-param callable(T, array-key):TMapResult|callable(T):TMapResult $mapFn
     *
     * @return iterable<int, TMapResult>
     */
    public function map(callable $mapFn): iterable;

    /**
     * Returns an iterable list of values, that are pulled of each item by its property name, getter name or array index.
     */
    public function column(?string $valueAccessor, ?string $keyAccessor = null): iterable;

    /**
     * Group items in maximum $size big chunks. The last chunk can be less than $size items.
     *
     * @phpstan-param positive-int $size
     *
     * @phpstan-return iterable<static&self<T>>
     */
    public function chunk(int $size): iterable;

    /**
     * Returns the items as a fixed size array. This is useful to use with methods that don't support iterables.
     *
     * @return array<T>
     */
    public function asArray(): array;

    /**
     * Returns true, when the item is in the collection, otherwise false.
     *
     * @param T $value
     */
    public function contains($value): bool;

    /**
     * Returns a copy of this collection only containing items a single time.
     */
    public function asUnique(): static;
}
