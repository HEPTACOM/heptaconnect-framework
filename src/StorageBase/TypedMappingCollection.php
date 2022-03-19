<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;

class TypedMappingCollection extends MappingCollection
{
    /**
     * @psalm-var class-string<DatasetEntityContract>
     */
    private string $type;

    /**
     * @psalm-param class-string<DatasetEntityContract> $type
     * @psalm-param iterable<int, MappingInterface>     $items
     */
    public function __construct(string $type, iterable $items = [])
    {
        $this->type = $type;

        parent::__construct($items);
    }

    /**
     * @psalm-return class-string<DatasetEntityContract>
     */
    public function getType(): string
    {
        return $this->type;
    }

    protected function isValidItem($item): bool
    {
        if (!parent::isValidItem($item)) {
            return false;
        }

        if (!$item instanceof MappingInterface) {
            return false;
        }

        return $item->getEntityType() === $this->type;
    }
}
