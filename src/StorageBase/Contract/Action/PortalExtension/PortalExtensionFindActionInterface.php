<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find\PortalExtensionFindResult;

interface PortalExtensionFindActionInterface
{
    /**
     * Find portal extension state on a portal node.
     */
    public function find(PortalNodeKeyInterface $portalNodeKey): PortalExtensionFindResult;
}
