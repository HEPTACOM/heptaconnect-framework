<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection\Contract;

use Heptacom\HeptaConnect\Utility\Json\JsonSerializeObjectVarsTrait;

/**
 * @template T
 */
final class TagItem implements \JsonSerializable
{
    use JsonSerializeObjectVarsTrait;

    /**
     * @param CollectionInterface<T> $collection
     */
    public function __construct(
        private CollectionInterface $collection,
        private string $tag
    ) {
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
     *
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
