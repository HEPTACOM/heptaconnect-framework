<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;

class TypedMappingCollection extends MappingCollection
{
    /**
     * @psalm-var class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    private string $type;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $type
     * @psalm-param array<array-key, \Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface> $items
     */
    public function __construct(string $type, array $items = [])
    {
        $this->type = $type;

        parent::__construct($items);
    }

    /**
     * @psalm-return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
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

        return $item->getDatasetEntityClassName() === $this->type;
    }
}
