<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

class Dependency extends DatasetEntity implements Contract\DependencyInterface
{
    use Support\DependencyTrait;

    /**
     * @psalm-param class-string<Contract\DatasetEntityInterface> $datasetEntityClass
     */
    public function __construct(string $datasetEntityClass, string $primaryKey)
    {
        $this->dependencies = new DependencyCollection();
        $this->datasetEntityClass = $datasetEntityClass;
        $this->primaryKey = $primaryKey;
    }
}
