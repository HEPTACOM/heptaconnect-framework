<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityDirection;

use Heptacom\HeptaConnect\Storage\Base\Action\IdentityDirection\Overview\IdentityDirectionOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\IdentityDirection\Overview\IdentityDirectionOverviewResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidOverviewCriteriaException;

interface IdentityDirectionOverviewActionInterface
{
    /**
     * Paginate over all directional identities.
     * Expected to be used for a human request listing.
     *
     * @throws InvalidOverviewCriteriaException
     *
     * @return iterable<IdentityDirectionOverviewResult>
     */
    public function overview(IdentityDirectionOverviewCriteria $criteria): iterable;
}
