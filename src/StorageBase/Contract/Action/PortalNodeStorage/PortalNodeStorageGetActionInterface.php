<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Get\PortalNodeStorageGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Get\PortalNodeStorageGetResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface PortalNodeStorageGetActionInterface
{
    /**
     * Get portal node storage entries from the storage.
     *
     * @throws UnsupportedStorageKeyException
     *
     * @return iterable<PortalNodeStorageGetResult>
     */
    public function get(PortalNodeStorageGetCriteria $criteria): iterable;
}
