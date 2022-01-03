<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\RouteCapability\Overview;

use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidOverviewCriteriaException;

interface RouteCapabilityOverviewActionInterface
{
    /**
     * @throws InvalidOverviewCriteriaException
     *
     * @return iterable<\Heptacom\HeptaConnect\Storage\Base\Contract\Action\RouteCapability\Overview\RouteCapabilityOverviewResult>
     */
    public function overview(RouteCapabilityOverviewCriteria $criteria): iterable;
}
