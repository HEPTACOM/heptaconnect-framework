<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidOverviewCriteriaException;

interface RouteCapabilityOverviewActionInterface
{
    /**
     * @throws InvalidOverviewCriteriaException
     *
     * @return iterable<RouteCapabilityOverviewResult>
     */
    public function overview(RouteCapabilityOverviewCriteria $criteria): iterable;
}
