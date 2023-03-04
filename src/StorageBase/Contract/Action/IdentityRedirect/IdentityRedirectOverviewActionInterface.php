<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\IdentityRedirect;

use Heptacom\HeptaConnect\Storage\Base\Action\IdentityRedirect\Overview\IdentityRedirectOverviewCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\IdentityRedirect\Overview\IdentityRedirectOverviewResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidOverviewCriteriaException;

interface IdentityRedirectOverviewActionInterface
{
    /**
     * Paginate over identity redirects.
     * Expected to be used for a human request listing.
     *
     *@throws InvalidOverviewCriteriaException
     *
     * @return iterable<IdentityRedirectOverviewResult>
     */
    public function overview(IdentityRedirectOverviewCriteria $criteria): iterable;
}
