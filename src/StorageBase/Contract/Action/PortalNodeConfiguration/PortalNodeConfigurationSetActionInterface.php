<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Set\PortalNodeConfigurationSetPayloads;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;

interface PortalNodeConfigurationSetActionInterface
{
    /**
     * Set configuration for portal nodes.
     * The content must only consist of nested scalars.
     *
     * @throws CreateException
     */
    public function set(PortalNodeConfigurationSetPayloads $payloads): void;
}
