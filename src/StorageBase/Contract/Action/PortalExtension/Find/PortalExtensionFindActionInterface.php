<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\Find;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

interface PortalExtensionFindActionInterface
{
    public function find(PortalNodeKeyInterface $portalNodeKey): PortalExtensionFindResult;
}
