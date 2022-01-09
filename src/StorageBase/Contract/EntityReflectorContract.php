<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

abstract class EntityReflectorContract
{
    abstract public function reflectEntities(
        MappedDatasetEntityCollection $mappedEntities,
        PortalNodeKeyInterface $targetPortalNodeKey
    ): void;
}
