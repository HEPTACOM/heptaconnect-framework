<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\Route\Overview;

use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidOverviewCriteriaException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface RouteOverviewActionInterface
{
    /**
     * @throws InvalidOverviewCriteriaException
     * @throws UnsupportedStorageKeyException
     *
     * @return iterable<RouteOverviewResult>
     */
    public function overview(RouteOverviewCriteria $criteria): iterable;
}
