<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Exception;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class InvalidPortalNodeKeyException extends \Exception
{
    private PortalNodeKeyInterface $portalNodeKey;

    public function __construct(PortalNodeKeyInterface $portalNodeKey, ?\Throwable $previous = null)
    {
        parent::__construct(\sprintf('Invalid portal node key: %s', \json_encode($portalNodeKey)), 0, $previous);

        $this->portalNodeKey = $portalNodeKey;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }
}
