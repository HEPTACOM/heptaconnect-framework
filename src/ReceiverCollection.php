<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;

class ReceiverCollection extends DatasetEntityCollection
{
    protected function getT(): string
    {
        return Contract\ReceiverInterface::class;
    }

    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $entityClassName
     *
     * @return iterable<\Heptacom\HeptaConnect\Portal\Base\Contract\ReceiverInterface>
     */
    public function bySupport(string $entityClassName): iterable
    {
        yield from $this->filter(function (Contract\ReceiverInterface $emitter) use ($entityClassName): bool {
            return \in_array($entityClassName, $emitter->supports(), true);
        });
    }
}
