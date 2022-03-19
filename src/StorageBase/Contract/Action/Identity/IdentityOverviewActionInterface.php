<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity;

use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Overview\IdentityOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Overview\IdentityOverviewResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidOverviewCriteriaException;

interface IdentityOverviewActionInterface
{
    /**
     * Paginate over all identities.
     * Expected to be used for a human request listing.
     *
     * @throws InvalidOverviewCriteriaException
     *
     * @return iterable<IdentityOverviewResult>
     */
    public function overview(IdentityOverviewCriteria $criteria): iterable;
}
