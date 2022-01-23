<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\Mapping\Map;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class MappingMapPayload
{
    private PortalNodeKeyInterface $portalNodeKey;

    private DatasetEntityCollection $entityCollection;

    public function __construct(PortalNodeKeyInterface $portalNodeKey, DatasetEntityCollection $entityCollection)
    {
        $this->portalNodeKey = $portalNodeKey;
        $this->entityCollection = $entityCollection;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getEntityCollection(): DatasetEntityCollection
    {
        return $this->entityCollection;
    }

    public function setEntityCollection(DatasetEntityCollection $entityCollection): void
    {
        $this->entityCollection = $entityCollection;
    }
}
