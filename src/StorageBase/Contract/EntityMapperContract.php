<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

abstract class EntityMapperContract
{
    /**
     * @psalm-param \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityCollection
     */
    abstract public function mapEntities(
        DatasetEntityCollection $entityCollection,
        PortalNodeKeyInterface $portalNodeKey
    ): MappedDatasetEntityCollection;
}
