<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Listing;

use Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Route\Listing\ReceptionRouteListResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface ReceptionRouteListActionInterface
{
    /**
     * @throws UnsupportedStorageKeyException
     *
     * @return iterable<ReceptionRouteListResult>
     */
    public function list(ReceptionRouteListCriteria $criteria): iterable;
}
