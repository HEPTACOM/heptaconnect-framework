<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;

class TypedMappingCollection extends MappingCollection
{
    /**
     * @param iterable<MappingInterface> $items
     */
    public function __construct(
        private readonly EntityType $entityType,
        iterable $items = []
    ) {
        parent::__construct($items);
    }

    public function getEntityType(): EntityType
    {
        return $this->entityType;
    }

    /**
     * @deprecated Use @see getEntityType instead
     */
    public function getType(): string
    {
        return (string) $this->entityType;
    }

    #[\Override]
    protected function isValidItem(mixed $item): bool
    {
        if (!parent::isValidItem($item)) {
            return false;
        }

        /* @var MappingInterface $item */
        return $item->getEntityType()->equals($this->entityType);
    }
}
