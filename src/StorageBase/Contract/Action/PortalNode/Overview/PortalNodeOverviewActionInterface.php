<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\Overview;

use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidOverviewCriteriaException;

interface PortalNodeOverviewActionInterface
{
    /**
     * @throws InvalidOverviewCriteriaException
     *
     * @return iterable<PortalNodeOverviewResult>
     */
    public function overview(PortalNodeOverviewCriteria $criteria): iterable;
}
