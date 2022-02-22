<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;

/**
 * @extends AbstractObjectCollection<ExplorerContract>
 */
class ExplorerCollection extends AbstractObjectCollection
{
    /**
     * @param class-string<DatasetEntityContract> $entityType
     *
     * @return iterable<array-key, ExplorerContract>
     */
    public function bySupport(string $entityType): iterable
    {
        return $this->filter(fn (ExplorerContract $explorer) => $entityType === $explorer->supports());
    }

    /**
     * @psalm-return Contract\ExplorerContract::class
     */
    protected function getT(): string
    {
        return ExplorerContract::class;
    }
}
