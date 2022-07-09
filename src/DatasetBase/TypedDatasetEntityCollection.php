<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

/**
 * @extends DatasetEntityCollection<DatasetEntityContract>
 */
final class TypedDatasetEntityCollection extends DatasetEntityCollection
{
    private EntityType $type;

    /**
     * @psalm-param class-string<DatasetEntityContract>|EntityType $type
     * @psalm-param iterable<int, DatasetEntityContract> $items
     */
    public function __construct($type, iterable $items = [])
    {
        $this->type = new EntityType((string) $type);

        parent::__construct($items);
    }

    /**
     * @deprecated use @see getEntityType instead
     *
     * @psalm-return class-string<DatasetEntityContract>
     */
    public function getType(): string
    {
        return $this->type->getClassString();
    }

    public function getEntityType(): EntityType
    {
        return $this->type;
    }

    /**
     * @param DatasetEntityContract $item
     */
    protected function isValidItem($item): bool
    {
        return parent::isValidItem($item) && $this->type->isObjectOfType($item);
    }
}
