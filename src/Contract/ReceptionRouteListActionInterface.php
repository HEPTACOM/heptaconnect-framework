<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

interface ReceptionRouteListActionInterface
{
    /**
     * @return iterable<ReceptionRouteListResult>
     */
    public function list(ReceptionRouteListCriteria $criteria): iterable;
}
