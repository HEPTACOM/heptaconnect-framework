<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface ReceptionRouteListActionInterface
{
    /**
     * @return iterable<ReceptionRouteListResult>
     *
     * @throws UnsupportedStorageKeyException
     */
    public function list(ReceptionRouteListCriteria $criteria): iterable;
}
