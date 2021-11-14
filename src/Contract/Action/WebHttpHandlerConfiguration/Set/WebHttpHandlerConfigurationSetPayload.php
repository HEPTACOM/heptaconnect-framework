<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\WebHttpHandlerConfiguration\Set;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

class WebHttpHandlerConfigurationSetPayload implements CreatePayloadInterface
{
    protected PortalNodeKeyInterface $portalNodeKey;

    private string $path;

    protected string $key;

    protected ?array $value = null;

    public function __construct(PortalNodeKeyInterface $portalNodeKey, string $path, string $key, ?array $value = null)
    {
        $this->portalNodeKey = $portalNodeKey;
        $this->path = $path;
        $this->key = $key;
        $this->value = $value;
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

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getValue(): ?array
    {
        return $this->value;
    }

    public function setValue(?array $value): void
    {
        $this->value = $value;
    }
}
