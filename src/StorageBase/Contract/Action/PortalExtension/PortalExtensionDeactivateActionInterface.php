<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Deactivate\PortalExtensionDeactivatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Deactivate\PortalExtensionDeactivateResult;

interface PortalExtensionDeactivateActionInterface
{
    /**
     * Deactivate portal extensions on a portal node.
     */
    public function deactivate(PortalExtensionDeactivatePayload $payload): PortalExtensionDeactivateResult;
}
