<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base;

use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;

class TypedMappedDatasetEntityCollection extends MappedDatasetEntityCollection
{
    /**
     * @psalm-var class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    private string $type;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $type
     * @psalm-param array<array-key, \Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct> $items
     */
    public function __construct(string $type, array $items = [])
    {
        $this->type = $type;

        parent::__construct($items);
    }

    /**
     * @psalm-return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
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
        return parent::isValidItem($item) && $item->getMapping()->getDatasetEntityClassName() === $this->type;
    }
}
