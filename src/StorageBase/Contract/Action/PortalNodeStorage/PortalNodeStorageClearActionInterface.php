<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Clear\PortalNodeStorageClearCriteria;
use Heptacom\HeptaConnect\Storage\Base\Exception\DeleteException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface PortalNodeStorageClearActionInterface
{
    /**
     * Delete all stored values in a portal node.
     *
     * @throws DeleteException
     * @throws UnsupportedStorageKeyException
     */
    public function clear(PortalNodeStorageClearCriteria $criteria): void;
}
