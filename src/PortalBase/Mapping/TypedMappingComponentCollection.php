<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingComponentStructContract;

class TypedMappingComponentCollection extends MappingComponentCollection
{
    /**
     * @psalm-var class-string<DatasetEntityContract>
     */
    private string $type;

    /**
     * @psalm-param class-string<DatasetEntityContract>           $type
     * @psalm-param iterable<int, MappingComponentStructContract> $items
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

    /**
     * @param MappingComponentStructContract $item
     */
    protected function isValidItem($item): bool
    {
        return parent::isValidItem($item) && $item->getEntityType() === $this->type;
    }
}
