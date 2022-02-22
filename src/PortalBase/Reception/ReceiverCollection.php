<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;

/**
 * @extends AbstractObjectCollection<ReceiverContract>
 */
class ReceiverCollection extends AbstractObjectCollection
{
    /**
     * @param class-string<DatasetEntityContract> $entityType
     *
     * @return iterable<ReceiverContract>
     */
    public function bySupport(string $entityType): iterable
    {
        return $this->filter(fn (ReceiverContract $emitter) => $entityType === $emitter->supports());
    }

    /**
     * @psalm-return Contract\ReceiverContract::class
     */
    protected function getT(): string
    {
        return ReceiverContract::class;
    }
}
