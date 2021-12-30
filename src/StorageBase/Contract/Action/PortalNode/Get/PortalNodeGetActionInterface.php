<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\Get;

use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface PortalNodeGetActionInterface
{
    /**
     * @throws UnsupportedStorageKeyException
     *
     * @return iterable<PortalNodeGetResult>
     */
    public function get(PortalNodeGetCriteria $criteria): iterable;
}
