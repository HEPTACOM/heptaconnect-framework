<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

trait DependencyTrait
{
    use PrimaryKeyTrait;

    /**
     * @psalm-var class-string<DatasetEntityContract>
     */
    protected string $datasetEntityClass = DatasetEntityContract::class;

    /**
     * @psalm-return class-string<DatasetEntityContract>
     */
    public function getDatasetEntityClass(): string
    {
        return $this->datasetEntityClass;
    }

    /**
     * @psalm-param class-string<DatasetEntityContract> $datasetEntityClass
     */
    public function setDatasetEntityClass(string $datasetEntityClass): void
    {
        $this->datasetEntityClass = $datasetEntityClass;
    }
}
