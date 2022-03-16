<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;

/**
 * @extends AbstractObjectCollection<MappingInterface>
 */
class MappingCollection extends AbstractObjectCollection
{
    /**
     * @return iterable<TypedMappingCollection>
     */
    public function groupByType(): iterable
    {
        $typedMappings = [];

        /** @var MappingInterface $mapping */
        foreach ($this->items as $mapping) {
            $entityType = $mapping->getEntityType();

            $typedMappings[$entityType] ??= new TypedMappingCollection($entityType);
            $typedMappings[$entityType]->push([$mapping]);
        }

        yield from $typedMappings;
    }

    protected function getT(): string
    {
        return MappingInterface::class;
    }
}
