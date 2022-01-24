<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route;

use Heptacom\HeptaConnect\Storage\Base\Action\Route\Delete\RouteDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;

interface RouteDeleteActionInterface
{
    /**
     * @throws NotFoundException
     */
    public function delete(RouteDeleteCriteria $criteria): void;
}
