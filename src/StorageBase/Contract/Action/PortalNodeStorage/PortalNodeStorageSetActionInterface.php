<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Set\PortalNodeStorageSetPayload;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface PortalNodeStorageSetActionInterface
{
    /**
     * Creates and updates portal node storage entries in the storage.
     *
     * @throws CreateException
     * @throws UnsupportedStorageKeyException
     */
    public function set(PortalNodeStorageSetPayload $payload): void;
}
