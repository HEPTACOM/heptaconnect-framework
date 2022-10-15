<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection;

/**
 * @template T
 * @extends AbstractCollection<TagItem<T>>
 */
abstract class AbstractTaggedCollection extends AbstractCollection
{
    /**
     * @psalm-param array-key $offset
     * @psalm-return TagItem<T>
     */
    public function offsetGet($offset)
    {
        $offset = (string) $offset;
        $tag = parent::offsetGet($offset);

        if ($tag !== null) {
            return $tag;
        }

        $tag = new TagItem($this->createEmptyCollection(), $offset);
        $this->offsetSet($offset, $tag);

        return $tag;
    }

    public function offsetSet($offset, $value): void
    {
        parent::offsetSet($value->getTag(), $value);
    }

    public function push(iterable $items): void
    {
        /** @psalm-var TagItem<T> $item */
        foreach (\iterator_to_array($this->filterValid($items)) as $item) {
            $this->offsetSet($item->getTag(), $item);
        }
    }

    protected function isValidItem($item): bool
    {
        /* @phpstan-ignore-next-line treatPhpDocTypesAsCertain checks soft check but this is the hard check */
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
