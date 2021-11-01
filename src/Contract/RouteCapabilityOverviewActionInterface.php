<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

interface RouteCapabilityOverviewActionInterface
{
    /**
     * @return iterable<RouteCapabilityOverviewResult>
     */
    public function overview(RouteCapabilityOverviewCriteria $criteria): iterable;
}
