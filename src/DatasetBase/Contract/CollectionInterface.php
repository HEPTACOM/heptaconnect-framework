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
     * @psalm-return \Generator<int, T>
     */
    public function filter(callable $filterFn): \Generator;

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
}
