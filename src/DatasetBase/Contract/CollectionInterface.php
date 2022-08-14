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
     * @template TMapResult
     *
     * @psalm-param callable(T, array-key):TMapResult|callable(T):TMapResult $mapFn
     *
     * @return iterable<int, TMapResult>
     */
    public function map(callable $mapFn): iterable;

    public function column(string $valueAccessor, ?string $keyAccessor = null): iterable;
}
