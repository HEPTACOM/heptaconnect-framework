<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Mapping\Map;

use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;

class MappingMapResult
{
    private MappedDatasetEntityCollection $mappedDatasetEntityCollection;

    public function __construct(MappedDatasetEntityCollection $mappedDatasetEntityCollection)
    {
        $this->mappedDatasetEntityCollection = $mappedDatasetEntityCollection;
    }

    public function getMappedDatasetEntityCollection(): MappedDatasetEntityCollection
    {
        return $this->mappedDatasetEntityCollection;
    }
}
