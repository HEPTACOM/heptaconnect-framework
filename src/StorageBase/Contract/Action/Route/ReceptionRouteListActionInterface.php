<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route;

use Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface ReceptionRouteListActionInterface
{
    /**
     * List all routes for a reception scenario.
     *
     * @throws UnsupportedStorageKeyException
     *
     * @return iterable<ReceptionRouteListResult>
     */
    public function list(ReceptionRouteListCriteria $criteria): iterable;
}
