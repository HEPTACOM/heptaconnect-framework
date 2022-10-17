<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\File;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

abstract class FileReferenceContract implements AttachableInterface
{
    public function __construct(private PortalNodeKeyInterface $portalNodeKey)
    {
    }

    /**
     * Get the portal node the file reference is created in.
     */
    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }
}
