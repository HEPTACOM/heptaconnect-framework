<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate\PortalExtensionActivatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Activate\PortalExtensionActivateResult;

interface PortalExtensionActivateActionInterface
{
    public function activate(PortalExtensionActivatePayload $payload): PortalExtensionActivateResult;
}
