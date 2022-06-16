<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeAlias;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Get\PortalNodeAliasGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Get\PortalNodeAliasGetResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\ReadException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface PortalNodeAliasGetActionInterface
{
    /**
     * Get portal nodes and their aliases looked up by the given portal node keys.
     *
     * @throws ReadException
     * @throws UnsupportedStorageKeyException
     *
     * @return iterable<PortalNodeAliasGetResult>
     */
    public function get(PortalNodeAliasGetCriteria $criteria): iterable;
}
