<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;

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
        return $this->filter(static function (Contract\ReceiverContract $emitter) use ($entityClassName): bool {
            return \in_array($entityClassName, $emitter->supports(), true);
        });
    }

    protected function getT(): string
    {
        return Contract\ReceiverContract::class;
    }
}
