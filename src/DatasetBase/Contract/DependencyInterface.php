<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

interface DependencyInterface extends AttachableInterface
{
    /**
     * @psalm-return class-string<DatasetEntityContract>
     */
    public function getDatasetEntityClass(): string;

    /**
     * @psalm-param class-string<DatasetEntityContract> $datasetEntityClass
     */
    public function setDatasetEntityClass(string $datasetEntityClass): void;
}
