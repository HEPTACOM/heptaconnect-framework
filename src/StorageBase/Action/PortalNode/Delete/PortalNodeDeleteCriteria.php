<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;

class PortalNodeDeleteCriteria
{
    private PortalNodeKeyCollection $portalNodeKeys;

    public function __construct(PortalNodeKeyCollection $portalNodeKeys)
    {
        $this->portalNodeKeys = $portalNodeKeys;
    }

    public function getPortalNodeKeys(): PortalNodeKeyCollection
    {
        return $this->portalNodeKeys;
    }

    public function setPortalNodeKeys(PortalNodeKeyCollection $portalNodeKeys): void
    {
        $this->portalNodeKeys = $portalNodeKeys;
    }
}