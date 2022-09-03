<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;

/**
 * @extends AbstractObjectCollection<ExplorerContract>
 */
class ExplorerCollection extends AbstractObjectCollection
{
    /**
     * @return iterable<int, ExplorerContract>
     */
    public function bySupport(EntityType $entityType): iterable
    {
        return $this->filter(
            static fn (ExplorerContract $explorer): bool => $entityType->equals($explorer->getSupportedEntityType())
        );
    }

    /**
     * @psalm-return Contract\ExplorerContract::class
     */
    protected function getT(): string
    {
        return ExplorerContract::class;
    }
}
