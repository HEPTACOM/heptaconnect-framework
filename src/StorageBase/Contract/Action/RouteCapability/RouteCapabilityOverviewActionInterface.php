<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\RouteCapability;

use Heptacom\HeptaConnect\Storage\Base\Action\RouteCapability\Overview\RouteCapabilityOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidOverviewCriteriaException;

interface RouteCapabilityOverviewActionInterface
{
    /**
     * @throws InvalidOverviewCriteriaException
     *
     * @return iterable<\Heptacom\HeptaConnect\Storage\Base\Action\RouteCapability\Overview\RouteCapabilityOverviewResult>
     */
    public function overview(RouteCapabilityOverviewCriteria $criteria): iterable;
}
