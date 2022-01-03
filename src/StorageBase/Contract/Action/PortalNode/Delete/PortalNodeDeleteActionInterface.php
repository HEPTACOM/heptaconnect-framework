<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\Delete;

use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;

interface PortalNodeDeleteActionInterface
{
    /**
     * @throws NotFoundException
     */
    public function delete(PortalNodeDeleteCriteria $criteria): void;
}
