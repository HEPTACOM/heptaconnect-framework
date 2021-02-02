<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;

/**
 * @extends DatasetEntityCollection<\Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract>
 */
class ExplorerCollection extends DatasetEntityCollection
{
    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityClassName
     *
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract>
     */
    public function bySupport(string $entityClassName): iterable
    {
        return $this->filter(fn (ExplorerContract $explorer) => $entityClassName === $explorer->supports());
    }

    protected function getT(): string
    {
        return ExplorerContract::class;
    }
}
