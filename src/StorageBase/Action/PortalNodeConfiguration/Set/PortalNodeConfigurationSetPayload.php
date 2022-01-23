<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Set;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

class PortalNodeConfigurationSetPayload implements CreatePayloadInterface
{
    private PortalNodeKeyInterface $portalNodeKey;

    private ?array $value;

    public function __construct(PortalNodeKeyInterface $portalNodeKey, ?array $value)
    {
        $this->portalNodeKey = $portalNodeKey;
        $this->value = $value;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setValue(?array $value): void
    {
        $this->value = $value;
    }

    public function getValue(): ?array
    {
        return $this->value;
    }
}
