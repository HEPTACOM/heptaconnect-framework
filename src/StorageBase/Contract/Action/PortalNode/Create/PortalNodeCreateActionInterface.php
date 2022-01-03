<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\Create;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreateResults;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;

interface PortalNodeCreateActionInterface
{
    /**
     * @throws CreateException
     */
    public function create(PortalNodeCreatePayloads $payloads): PortalNodeCreateResults;
}
