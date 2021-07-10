<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception\Contract;

use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;

interface ReceiverStackInterface
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface>
     */
    public function next(
        TypedDatasetEntityCollection $entities,
        ReceiveContextInterface $context
    ): iterable;
}
