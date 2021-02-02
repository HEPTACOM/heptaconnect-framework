<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Publication\Contract;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

interface PublisherInterface
{
    /**
     * @param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $datasetEntityClassName
     */
    public function publish(
        string $datasetEntityClassName,
        PortalNodeKeyInterface $portalNodeId,
        string $externalId
    ): MappingInterface;

    public function publishBatch(MappingCollection $mappings): void;
}
