<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

interface DependencyInterface extends DatasetEntityInterface
{
    /**
     * @return class-string<DatasetEntityInterface>
     */
    public function getDatasetEntityClass(): string;

    /**
     * @psalm-param class-string<DatasetEntityInterface> $datasetEntityClass
     */
    public function setDatasetEntityClass(string $datasetEntityClass): void;
}
