<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\JsonSerializeObjectVarsTrait;

/**
 * @template T
 */
final class TagItem implements \JsonSerializable
{
    use JsonSerializeObjectVarsTrait;

    /**
     * @psalm-var CollectionInterface<T>
     */
    private CollectionInterface $collection;

    private string $tag;

    /**
     * @psalm-param CollectionInterface<T> $collection
     */
    public function __construct(CollectionInterface $collection, string $tag)
    {
        $this->collection = $collection;
        $this->tag = $tag;
    }

    /**
     * @psalm-return CollectionInterface<T>
     */
    public function getCollection(): CollectionInterface
    {
        return $this->collection;
    }

    /**
     * @psalm-param CollectionInterface<T> $collection
     * @psalm-return TagItem<T>
     */
    public function setCollection(CollectionInterface $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    public function getTag(): string
    {
        return $this->tag;
    }
}
