<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<ExplorerContract>
 */
class ExplorerCollection extends AbstractObjectCollection
{
    public function bySupport(EntityType $entityType): static
    {
        return $this->filter(
            static fn (ExplorerContract $explorer): bool => $entityType->equals($explorer->getSupportedEntityType())
        );
    }

    protected function getT(): string
    {
        return ExplorerContract::class;
    }
}
