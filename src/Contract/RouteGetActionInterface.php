<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface RouteGetActionInterface
{
    /**
     * @return iterable<RouteGetResult>
     *
     * @throws UnsupportedStorageKeyException
     */
    public function get(RouteGetCriteria $criteria): iterable;
}
