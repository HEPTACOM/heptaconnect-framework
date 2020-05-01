<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

interface DependencyInterface extends DatasetEntityInterface
{
    public function getDatasetEntityClass(): string;

    public function setDatasetEntityClass(string $datasetEntityClass): void;
}
