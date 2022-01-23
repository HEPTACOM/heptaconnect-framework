<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class PortalNodeConfigurationGetResult
{
    private PortalNodeKeyInterface $portalNodeKey;

    private array $value;

    public function __construct(PortalNodeKeyInterface $portalNodeKey, array $value)
    {
        $this->portalNodeKey = $portalNodeKey;
        $this->value = $value;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getValue(): array
    {
        return $this->value;
    }
}
