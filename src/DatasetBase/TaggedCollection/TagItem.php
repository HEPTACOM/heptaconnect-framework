<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\TaggedCollection;

use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\JsonSerializeObjectVarsTrait;

/**
 * @template T
 */
class TagItem implements \JsonSerializable
{
    use JsonSerializeObjectVarsTrait;

    /**
     * @psalm-var \Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface<T>
     */
    protected CollectionInterface $collection;

    protected string $tag;

    /**
     * @psalm-param \Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface<T> $collection
     */
    public function __construct(CollectionInterface $collection, string $tag)
    {
        $this->collection = $collection;
        $this->tag = $tag;
    }

    /**
     * @psalm-return \Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface<T>
     */
    public function getCollection(): CollectionInterface
    {
        return $this->collection;
    }

    /**
     * @psalm-param \Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface<T> $collection
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
