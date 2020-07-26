<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection<\Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface>
 */
class MappingCollection extends DatasetEntityCollection
{
    /**
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\Mapping\TypedMappingCollection>
     */
    public function groupByType(): iterable
    {
        $typedMappings = [];

        /** @var MappingInterface $mapping */
        foreach ($this->items as $mapping) {
            $entityClassName = $mapping->getDatasetEntityClassName();

            $typedMappings[$entityClassName] ??= new TypedMappingCollection($entityClassName);
            $typedMappings[$entityClassName]->push([$mapping]);
        }

        yield from $typedMappings;
    }

    protected function getT(): string
    {
        return MappingInterface::class;
    }
}
