<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\Deactivate;

interface PortalExtensionDeactivateActionInterface
{
    public function deactivate(PortalExtensionDeactivatePayload $payload): PortalExtensionDeactivateResult;
}
