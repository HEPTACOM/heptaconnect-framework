<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeStorage;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Listing\PortalNodeStorageListCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Listing\PortalNodeStorageListResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface PortalNodeStorageListActionInterface
{
    /**
     * Get all portal node storage entries from the storage.
     *
     * @throws UnsupportedStorageKeyException
     *
     * @return iterable<PortalNodeStorageListResult>
     */
    public function list(PortalNodeStorageListCriteria $criteria): iterable;
}
