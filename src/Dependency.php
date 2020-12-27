<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

class Dependency implements Contract\DependencyInterface
{
    use Support\DependencyTrait;

    /**
     * @psalm-param class-string<Contract\DatasetEntityInterface> $datasetEntityClass
     */
    public function __construct(string $datasetEntityClass, string $primaryKey)
    {
        $this->datasetEntityClass = $datasetEntityClass;
        $this->primaryKey = $primaryKey;
    }
}
