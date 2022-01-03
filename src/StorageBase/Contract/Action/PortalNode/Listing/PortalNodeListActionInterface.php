<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\Listing;

interface PortalNodeListActionInterface
{
    /**
     * @return iterable<PortalNodeListResult>
     */
    public function list(): iterable;
}
