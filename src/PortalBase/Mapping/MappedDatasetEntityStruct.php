<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;

final class MappedDatasetEntityStruct
{
    protected MappingInterface $mapping;

    protected DatasetEntityContract $datasetEntity;

    public function __construct(MappingInterface $mapping, DatasetEntityContract $datasetEntity)
    {
        $this->mapping = $mapping;
        $this->datasetEntity = $datasetEntity;
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
