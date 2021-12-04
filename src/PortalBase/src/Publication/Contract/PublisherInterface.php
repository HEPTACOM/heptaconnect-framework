<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Publication\Contract;

use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

interface PublisherInterface
{
    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityType
     *
     * @deprecated Use publishBatch instead
     */
    public function publish(
        string $entityType,
        PortalNodeKeyInterface $portalNodeId,
        string $externalId
    ): void;

    public function publishBatch(MappingComponentCollection $mappings): void;
}