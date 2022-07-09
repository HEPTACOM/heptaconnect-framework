<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;

/**
 * @extends AbstractObjectCollection<ReceiverContract>
 */
class ReceiverCollection extends AbstractObjectCollection
{
    /**
     * @return iterable<int, ReceiverContract>
     */
    public function bySupport(EntityType $entityType): iterable
    {
        return $this->filter(
            static fn (ReceiverContract $receiver): bool => $entityType->same($receiver->getSupportedEntityType())
        );
    }

    /**
     * @psalm-return Contract\ReceiverContract::class
     */
    protected function getT(): string
    {
        return ReceiverContract::class;
    }
}
