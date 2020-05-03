<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface;
use Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityCollection;

interface ReceiverInterface
{
    /**
     * @return iterable<string, DatasetEntityInterface>
     */
    public function receive(
        MappedDatasetEntityCollection $mappedDatasetEntities,
        ReceiveContextInterface $context
    ): iterable;

    /**
     * @return array<array-key, class-string<\Heptacom\HeptaConnect\Portal\Base\Contract\MappingInterface>>
     */
    public function supports(): array;
}
