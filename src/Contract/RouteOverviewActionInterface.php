<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Storage\Base\Exception\InvalidOverviewCriteriaException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface RouteOverviewActionInterface
{
    /**
     * @return iterable<RouteOverviewResult>
     *
     * @throws InvalidOverviewCriteriaException
     * @throws UnsupportedStorageKeyException
     */
    public function overview(RouteOverviewCriteria $criteria): iterable;
}
