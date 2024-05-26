<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<ReceiverContract>
 */
class ReceiverCollection extends AbstractObjectCollection
{
    public function bySupport(EntityType $entityType): static
    {
        return $this->filter(
            static fn (ReceiverContract $receiver): bool => $entityType->equals($receiver->getSupportedEntityType())
        );
    }

    protected function getT(): string
    {
        return ReceiverContract::class;
    }
}
