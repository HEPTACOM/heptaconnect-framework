<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract>
 */
class ExplorerCollection extends AbstractObjectCollection
{
    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityType
     *
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract>
     */
    public function bySupport(string $entityType): iterable
    {
        return $this->filter(fn (ExplorerContract $explorer) => $entityType === $explorer->supports());
    }

    protected function getT(): string
    {
        return ExplorerContract::class;
    }
}
