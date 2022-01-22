<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Set\PortalNodeConfigurationSetPayloads;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;

interface PortalNodeConfigurationSetActionInterface
{
    /**
     * @throws CreateException
     */
    public function set(PortalNodeConfigurationSetPayloads $payloads): void;
}
