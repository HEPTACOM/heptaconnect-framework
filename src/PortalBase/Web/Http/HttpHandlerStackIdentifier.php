<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Web\Http;

use Heptacom\HeptaConnect\Portal\Base\FlowComponent\Contract\FlowComponentStackIdentifierInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class HttpHandlerStackIdentifier implements FlowComponentStackIdentifierInterface
{
    private PortalNodeKeyInterface $portalNodeKey;

    private string $path;

    public function __construct(PortalNodeKeyInterface $portalNodeKey, string $path)
    {
        $this->portalNodeKey = $portalNodeKey;
        $this->path = $path;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'portalNodeKey' => $this->getPortalNodeKey(),
            'path' => $this->getPath(),
        ];
    }
}
