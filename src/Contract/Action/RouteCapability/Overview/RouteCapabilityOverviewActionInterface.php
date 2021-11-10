<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\RouteCapability\Overview;

use Heptacom\HeptaConnect\Storage\Base\Contract\Action\RouteCapability\Overview\RouteCapabilityOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\RouteCapability\Overview\RouteCapabilityOverviewResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidOverviewCriteriaException;

interface RouteCapabilityOverviewActionInterface
{
    /**
     * @return iterable<\Heptacom\HeptaConnect\Storage\Base\Contract\Action\RouteCapability\Overview\RouteCapabilityOverviewResult>
     * @throws InvalidOverviewCriteriaException
     *
     */
    public function overview(RouteCapabilityOverviewCriteria $criteria): iterable;
}
