<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface PortalNodeGetActionInterface
{
    /**
     * Read portal node details.
     *
     * @throws UnsupportedStorageKeyException
     *
     * @return iterable<PortalNodeGetResult>
     */
    public function get(PortalNodeGetCriteria $criteria): iterable;
}
