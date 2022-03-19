<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Listing\PortalNodeListResult;

interface PortalNodeListActionInterface
{
    /**
     * List all portal nodes.
     *
     * @return iterable<PortalNodeListResult>
     */
    public function list(): iterable;
}
