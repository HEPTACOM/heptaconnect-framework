<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\RouteCapability;

use Heptacom\HeptaConnect\Storage\Base\Action\RouteCapability\Overview\RouteCapabilityOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\RouteCapability\Overview\RouteCapabilityOverviewResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidOverviewCriteriaException;

interface RouteCapabilityOverviewActionInterface
{
    /**
     * Paginate over all route capabilities.
     * Expected to be used for a human request listing.
     *
     * @throws InvalidOverviewCriteriaException
     *
     * @return iterable<RouteCapabilityOverviewResult>
     */
    public function overview(RouteCapabilityOverviewCriteria $criteria): iterable;
}
