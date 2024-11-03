<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection;

use Heptacom\HeptaConnect\Utility\Collection\Contract\IterableInterface;
use Heptacom\HeptaConnect\Utility\Php\SetStateTrait;

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
    use SetStateTrait;

    /**
     * @var array<int, T>
     */
    protected array $items;

    /**
     * Make sure to override @see recreateWithNewItems when changing the constructor signature.
     *
     * @param iterable<T> $items
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(iterable $items = [])
    {
        $this->items = \array_values(\iterable_to_array($this->validateItems($items)));
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
