<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection\Contract;

/**
 * Describes a mutable collection resembling classes.
 *
 * @template T
 *
 * @template-extends IterableInterface<T>
 */
interface CollectionInterface extends IterableInterface
{
    /**
     * Add new items to the collection.
     *
     * @param iterable<T> $items
     *
     * @throws \InvalidArgumentException
     */
    public function push(iterable $items): void;

    /**
     * Add new items to the collection but skip items, that do not meet the collection's validation criteria.
     *
     * @param iterable<T|mixed|null> $items
     */
    public function pushIgnoreInvalidItems(iterable $items): void;

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
     * Create a new collection of the same type, but without any content.
     */
    public function withoutItems(): static;

    /**
     * Reorders the collection into the opposite order it is now.
     */
    public function reverse(): void;
}
