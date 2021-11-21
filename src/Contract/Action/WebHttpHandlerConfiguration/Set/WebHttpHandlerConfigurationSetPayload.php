<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\Set;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

class WebHttpHandlerConfigurationSetPayload implements CreatePayloadInterface
{
    protected PortalNodeKeyInterface $portalNodeKey;

    protected string $path;

    protected string $configurationKey;

    protected ?array $configurationValue = null;

    public function __construct(
        PortalNodeKeyInterface $portalNodeKey,
        string $path,
        string $configurationKey,
        ?array $value = null
    ) {
        $this->portalNodeKey = $portalNodeKey;
        $this->path = $path;
        $this->configurationKey = $configurationKey;
        $this->configurationValue = $value;
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

    public function getConfigurationValue(): ?array
    {
        return $this->configurationValue;
    }

    public function setConfigurationValue(?array $configurationValue): void
    {
        $this->configurationValue = $configurationValue;
    }
}
