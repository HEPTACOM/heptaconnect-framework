<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

final class Dependency implements Contract\DependencyInterface
{
    use Support\DependencyTrait;

    /**
     * @psalm-param class-string<Contract\DatasetEntityContract> $datasetEntityClass
     */
    public function __construct(string $datasetEntityClass, string $primaryKey)
    {
        $this->datasetEntityClass = $datasetEntityClass;
        $this->primaryKey = $primaryKey;
    }
}
