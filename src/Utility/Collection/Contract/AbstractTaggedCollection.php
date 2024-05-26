<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection\Contract;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;

/**
 * @template T
 *
 * @extends AbstractCollection<TagItem<T>>
 */
abstract class AbstractTaggedCollection extends AbstractCollection
{
    /**
     * @var array<string, TagItem<T>>
     */
    protected array $items = [];

    /**
     * @phpstan-param array-key $offset
     *
     * @phpstan-return TagItem<T>
     */
    #[\Override]
    public function offsetGet($offset): mixed
    {
        $offset = (string) $offset;
        $tag = $this->items[$offset] ?? null;

        if ($tag !== null) {
            return $tag;
        }

        $tag = new TagItem($this->createEmptyCollection(), $offset);
        $this->items[$offset] = $tag;

        return $tag;
    }

    #[\Override]
    public function offsetSet($offset, $value): void
    {
        if (!$this->isValidItem($value)) {
            throw new \InvalidArgumentException();
        }

        $this->items[$value->getTag()] = $value;
    }

    #[\Override]
    public function push(iterable $items): void
    {
        /** @var TagItem<T> $item */
        foreach (\iterable_to_array($this->validateItems($items)) as $item) {
            $this->offsetSet($item->getTag(), $item);
        }
    }

    /**
     * @phpstan-assert-if-true TagItem<T> $item
     */
    #[\Override]
    protected function isValidItem(mixed $item): bool
    {
        return \is_object($item) && $item instanceof TagItem && \is_a($item->getCollection(), $this->getCollectionType(), false);
    }

    /**
     * @phpstan-return class-string<CollectionInterface<T>>
     */
    abstract protected function getCollectionType(): string;

    /**
     * @phpstan-return CollectionInterface<T>
     */
    private function createEmptyCollection(): CollectionInterface
    {
        $collectionType = $this->getCollectionType();

        return new $collectionType();
    }
}
