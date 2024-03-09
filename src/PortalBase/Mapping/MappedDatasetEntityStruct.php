<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;

class MappedDatasetEntityStruct
{
    public function __construct(
        protected MappingInterface $mapping,
        protected DatasetEntityContract $datasetEntity
    ) {
    }

    public function getMapping(): MappingInterface
    {
        return $this->mapping;
    }

    public function getDatasetEntity(): DatasetEntityContract
    {
        return $this->datasetEntity;
    }
}
