<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\Find;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class WebHttpHandlerConfigurationFindCriteria
{
    private PortalNodeKeyInterface $portalNodeKey;

    private string $path;

    private string $configurationKey;

    public function __construct(PortalNodeKeyInterface $portalNodeKey, string $path, string $configurationKey)
    {
        $this->portalNodeKey = $portalNodeKey;
        $this->path = $path;
        $this->configurationKey = $configurationKey;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getConfigurationKey(): string
    {
        return $this->configurationKey;
    }

    public function setConfigurationKey(string $configurationKey): void
    {
        $this->configurationKey = $configurationKey;
    }
}
