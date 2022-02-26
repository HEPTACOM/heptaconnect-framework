<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

/**
 * @extends DatasetEntityCollection<DatasetEntityContract>
 */
class TypedDatasetEntityCollection extends DatasetEntityCollection
{
    /**
     * @psalm-var class-string<DatasetEntityContract>
     */
    private string $type;

    /**
     * @psalm-param class-string<DatasetEntityContract> $type
     * @psalm-param iterable<int, DatasetEntityContract> $items
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
     * @param DatasetEntityContract $item
     */
    protected function isValidItem($item): bool
    {
        return parent::isValidItem($item) && \is_a($item, $this->type);
    }
}
