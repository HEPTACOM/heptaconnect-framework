<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Delete\PortalNodeStorageDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Exception\DeleteException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface PortalNodeStorageDeleteActionInterface
{
    /**
     * Delete stored values in a portal node by key and expiration date.
     *
     * @throws DeleteException
     * @throws UnsupportedStorageKeyException
     */
    public function delete(PortalNodeStorageDeleteCriteria $criteria): void;
}
