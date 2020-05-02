<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\MappingInterface;

class MappedDatasetEntityStruct
{
    protected MappingInterface $mapping;

    protected DatasetEntityInterface $datasetEntity;

    public function __construct(MappingInterface $mapping, DatasetEntityInterface $datasetEntity)
    {
        $this->mapping = $mapping;
        $this->datasetEntity = $datasetEntity;
    }

    public function getMapping(): MappingInterface
    {
        return $this->mapping;
    }

    public function getDatasetEntity(): DatasetEntityInterface
    {
        return $this->datasetEntity;
    }
}
