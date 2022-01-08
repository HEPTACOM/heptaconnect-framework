<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract>
 */
class ReceiverCollection extends AbstractObjectCollection
{
    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityType
     *
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract>
     */
    public function bySupport(string $entityType): iterable
    {
        return $this->filter(fn (ReceiverContract $emitter) => $entityType === $emitter->supports());
    }

    protected function getT(): string
    {
        return ReceiverContract::class;
    }
}
