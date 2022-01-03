<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route;

use Heptacom\HeptaConnect\Storage\Base\Action\Route\Find\RouteFindCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Find\RouteFindResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface RouteFindActionInterface
{
    /**
     * @throws UnsupportedStorageKeyException
     */
    public function find(RouteFindCriteria $criteria): ?RouteFindResult;
}
