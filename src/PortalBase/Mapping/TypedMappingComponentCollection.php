<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;

class TypedMappingComponentCollection extends MappingComponentCollection
{
    private EntityType $entityType;

    /**
     * @psalm-param iterable<int, MappingComponentStructContract> $items
     */
    public function __construct(EntityType $entityType, iterable $items = [])
    {
        $this->entityType = $entityType;

        parent::__construct($items);
    }

    public function getEntityType(): EntityType
    {
        return $this->entityType;
    }

    /**
     * @deprecated use @see getEntityType instead
     *
     * @psalm-return class-string<DatasetEntityContract>
     */
    public function getType(): string
    {
        return $this->entityType->getClassString();
    }

    /**
     * @param MappingComponentStructContract $item
     */
    protected function isValidItem($item): bool
    {
        return parent::isValidItem($item) && $item->getEntityType()->same($this->entityType);
    }
}
