<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Overview\PortalNodeOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Overview\PortalNodeOverviewResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidOverviewCriteriaException;

interface PortalNodeOverviewActionInterface
{
    /**
     * Paginate over all portal nodes.
     * Expected to be used for a human request listing.
     *
     * @throws InvalidOverviewCriteriaException
     *
     * @return iterable<PortalNodeOverviewResult>
     */
    public function overview(PortalNodeOverviewCriteria $criteria): iterable;
}
