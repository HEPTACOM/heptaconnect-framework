<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Get;

use Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Get\RouteGetResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface RouteGetActionInterface
{
    /**
     * @throws UnsupportedStorageKeyException
     *
     * @return iterable<RouteGetResult>
     */
    public function get(RouteGetCriteria $criteria): iterable;
}
