<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

interface RouteOverviewActionInterface
{
    /**
     * @return iterable<RouteOverviewResult>
     */
    public function overview(RouteOverviewCriteria $criteria): iterable;
}
