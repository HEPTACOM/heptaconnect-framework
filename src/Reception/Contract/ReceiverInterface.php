<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception\Contract;

use Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityCollection;

interface ReceiverInterface
{
    /**
     * @return iterable<array-key, MappingInterface>
     */
    public function receive(
        MappedDatasetEntityCollection $mappedDatasetEntities,
        ReceiveContextInterface $context,
        ReceiverStackInterface $stack
    ): iterable;

    /**
     * @return array<array-key, class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>>
     */
    public function supports(): array;
}
