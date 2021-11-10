<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidOverviewCriteriaException;

interface RouteCapabilityOverviewActionInterface
{
    /**
     * @return iterable<RouteCapabilityOverviewResult>
     *
     * @throws InvalidOverviewCriteriaException
     */
    public function overview(RouteCapabilityOverviewCriteria $criteria): iterable;
}
