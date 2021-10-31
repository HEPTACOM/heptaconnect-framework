<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface RouteFindByTargetsAndTypeActionInterface
{
    /**
     * @throws UnsupportedStorageKeyException
     */
    public function find(RouteFindByTargetsAndTypeCriteria $criteria): ?RouteFindByTargetsAndTypeResult;
}
