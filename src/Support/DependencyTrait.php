<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface;

trait DependencyTrait
{
    use PrimaryKeyTrait;

    /**
     * @psalm-var class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    protected string $datasetEntityClass = DatasetEntityInterface::class;

    /**
     * @psalm-return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    public function getDatasetEntityClass(): string
    {
        return $this->datasetEntityClass;
    }

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $datasetEntityClass
     */
    public function setDatasetEntityClass(string $datasetEntityClass): void
    {
        $this->datasetEntityClass = $datasetEntityClass;
    }
}
