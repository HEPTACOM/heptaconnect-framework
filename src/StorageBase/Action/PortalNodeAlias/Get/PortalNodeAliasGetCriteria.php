<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Get;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class PortalNodeAliasGetCriteria
{
    /**
     * @var array<PortalNodeKeyInterface>
     */
    private array $portalNodeKeys;

    public function __construct(array $portalNodeKeys)
    {
        $this->portalNodeKeys = $portalNodeKeys;
    }

    public function getPortalNodeKeys(): array
    {
        return $this->portalNodeKeys;
    }

    public function setPortalNodeKeys(array $portalNodeKeys): void
    {
        $this->portalNodeKeys = $portalNodeKeys;
    }
}
