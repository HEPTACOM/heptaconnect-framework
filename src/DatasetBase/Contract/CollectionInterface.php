<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

/**
 * Describes collection resembling classes.
 *
 * @template T
 * @extends \ArrayAccess<int, T>
 * @extends \IteratorAggregate<int, T>
 */
interface CollectionInterface extends \IteratorAggregate, \Countable, \ArrayAccess, \JsonSerializable
{
    /**
     * Add new items to the collection.
     *
     * @psalm-param iterable<int, T> $items
     */
    public function push(iterable $items): void;

    /**
     * Removes and returns the last element of the collection.
     * When the collection is empty, null is returned.
     *
     * @return T|null
     */
    public function pop();

    /**
     * Removes and returns the first element of the collection.
     * When the collection is empty, null is returned.
     *
     * @return T|null
     */
    public function shift();

    /**
     * Removes all entries of the collection.
     */
    public function clear(): void;

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
     *
     * @psalm-return \Generator<int, T>
     */
    public function filter(callable $filterFn): \Generator;

    /**
     * Returns an iterable list of anything, that is returned for each item by the given callable.
     *
     * @psalm-param callable(T, array-key):mixed|callable(T):mixed $mapFn
     *
     * @return iterable<int, T>
     */
    public function map(callable $mapFn): iterable;

    /**
     * Returns an iterable list of values, that are pulled of each item by its property name, getter name or array index.
     */
    public function column(string $valueAccessor, ?string $keyAccessor = null): iterable;
}
