<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeAlias;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Set\PortalNodeAliasSetPayloads;
use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidAliasSetPayloadException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UpdateException;

interface PortalNodeAliasSetActionInterface
{
    /**
     * @throws UpdateException
     * @throws InvalidAliasSetPayloadException
     */
    public function set(PortalNodeAliasSetPayloads $payloads): void;
}
