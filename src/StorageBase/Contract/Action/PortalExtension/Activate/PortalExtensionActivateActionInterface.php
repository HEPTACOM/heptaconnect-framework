<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\Activate;

interface PortalExtensionActivateActionInterface
{
    public function activate(PortalExtensionActivatePayload $payload): PortalExtensionActivateResult;
}
