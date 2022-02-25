<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

class TypedMappedDatasetEntityCollection extends MappedDatasetEntityCollection
{
    /**
     * @psalm-var class-string<DatasetEntityContract>
     */
    private string $type;

    /**
     * @psalm-param class-string<DatasetEntityContract>         $type
     * @psalm-param array<array-key, MappedDatasetEntityStruct> $items
     */
    public function __construct(string $type, array $items = [])
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

    /**
     * @param MappedDatasetEntityStruct $item
     */
    protected function isValidItem($item): bool
    {
        return parent::isValidItem($item) && $item->getMapping()->getEntityType() === $this->type;
    }
}
