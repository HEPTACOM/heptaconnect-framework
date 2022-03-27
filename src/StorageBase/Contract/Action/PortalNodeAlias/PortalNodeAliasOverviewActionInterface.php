<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeAlias;

use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Overview\PortalNodeAliasOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Overview\PortalNodeAliasOverviewResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidOverviewCriteriaException;

interface PortalNodeAliasOverviewActionInterface
{
    /**
     * Paginate over all portal node aliases.
     * Expected to be used for a human request listing.
     *
     * @throws InvalidOverviewCriteriaException
     *
     * @return iterable<PortalNodeAliasOverviewResult>
     */
    public function overview(PortalNodeAliasOverviewCriteria $criteria): iterable;
}
