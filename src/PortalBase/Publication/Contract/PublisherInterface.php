<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Publication\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

interface PublisherInterface
{
    /**
     * Publish existence of the given identities.
     * This will ensure the given identities are stored in the storage and queued for emission.
     */
    public function publishBatch(MappingComponentCollection $mappings): void;
}
