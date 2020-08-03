<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;

/**
 * @extends DatasetEntityCollection<\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract>
 */
class ReceiverCollection extends DatasetEntityCollection
{
    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $entityClassName
     *
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract>
     */
    public function bySupport(string $entityClassName): iterable
    {
        return $this->filter(fn (ReceiverContract $emitter) => \in_array($entityClassName, $emitter->supports(), true));
    }

    protected function getT(): string
    {
        return ReceiverContract::class;
    }
}
