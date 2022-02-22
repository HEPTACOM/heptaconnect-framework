<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeAlias;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Find\PortalNodeAliasFindCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Find\PortalNodeAliasFindResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\ReadException;

interface PortalNodeAliasFindActionInterface
{
    /**
     * @throws ReadException
     *
     * @return iterable<PortalNodeAliasFindResult>
     */
    public function find(PortalNodeAliasFindCriteria $criteria): iterable;
}
