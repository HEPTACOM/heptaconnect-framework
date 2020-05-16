<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

/**
 * @template T
 * @ method        __construct(T ...$items)
 * @ method T|null offsetGet(int|string $key)
 * @ method T|null first()
 * @ method T|null last()
 * @extends \ArrayAccess<array-key, T>
 * @extends \IteratorAggregate<array-key, T>
 */
interface DatasetEntityCollectionInterface extends \IteratorAggregate, \Countable, \ArrayAccess, \JsonSerializable
{
    /**
     * @psalm-param array<array-key, T> $items
     */
    public function push(array $items): void;

    public function clear(): void;

    /**
     * @return T|null
     */
    public function first();

    /**
     * @return T|null
     */
    public function last();
}
