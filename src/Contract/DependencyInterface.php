<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Contract;

interface DependencyInterface extends AttachableInterface
{
    /**
     * @psalm-return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    public function getDatasetEntityClass(): string;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $datasetEntityClass
     */
    public function setDatasetEntityClass(string $datasetEntityClass): void;
}
