<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeAlias;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Set\PortalNodeAliasSetPayloads;
use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidCreatePayloadException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UpdateException;

interface PortalNodeAliasSetActionInterface
{
    /**
     * Creates, updates and removes portal node aliases in the storage.
     *
     * @throws UpdateException
     * @throws InvalidCreatePayloadException
     */
    public function set(PortalNodeAliasSetPayloads $payloads): void;
}
