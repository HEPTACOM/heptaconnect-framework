<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Dataset\Base\EntityTypeClassString;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;

class TypedMappingCollection extends MappingCollection
{
    private EntityTypeClassString $entityType;

    /**
     * @psalm-param iterable<int, MappingInterface> $items
     */
    public function __construct(EntityTypeClassString $entityType, iterable $items = [])
    {
        $this->entityType = $entityType;

        parent::__construct($items);
    }

    public function getEntityType(): EntityTypeClassString
    {
        return $this->entityType;
    }

    /**
     * @deprecated Use @see getEntityType instead
     */
    public function getType(): string
    {
        return $this->entityType->getClassString();
    }

    protected function isValidItem($item): bool
    {
        if (!parent::isValidItem($item)) {
            return false;
        }

        if (!$item instanceof MappingInterface) {
            return false;
        }

        return $item->getEntityType()->same($this->entityType);
    }
}
