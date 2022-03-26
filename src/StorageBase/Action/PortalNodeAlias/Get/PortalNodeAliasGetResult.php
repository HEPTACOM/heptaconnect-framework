<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Get;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class PortalNodeAliasGetResult
{
    private PortalNodeKeyInterface $portalNodeKey;

    private string $alias;

    public function __construct(PortalNodeKeyInterface $portalNodeKey, string $alias)
    {
        $this->portalNodeKey = $portalNodeKey;
        $this->alias = $alias;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
}
