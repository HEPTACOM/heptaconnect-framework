<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection;

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
     * @psalm-param array-key $offset
     *
     * @psalm-return TagItem<T>
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
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

    public function offsetSet($offset, $value): void
    {
        if (!$this->isValidItem($value)) {
            throw new \InvalidArgumentException();
        }

        $this->items[$value->getTag()] = $value;
    }

    public function push(iterable $items): void
    {
        /** @var TagItem<T> $item */
        foreach (\iterable_to_array($this->validateItems($items)) as $item) {
            $this->offsetSet($item->getTag(), $item);
        }
    }

    /**
     * @psalm-assert-if-true TagItem<T> $item
     */
    protected function isValidItem(mixed $item): bool
    {
        return \is_object($item) && $item instanceof TagItem && \is_a($item->getCollection(), $this->getCollectionType(), false);
    }

    /**
     * @psalm-return class-string<CollectionInterface<T>>
     */
    abstract protected function getCollectionType(): string;

    /**
     * @psalm-return CollectionInterface<T>
     */
    private function createEmptyCollection(): CollectionInterface
    {
        $collectionType = $this->getCollectionType();

        return new $collectionType();
    }
}
