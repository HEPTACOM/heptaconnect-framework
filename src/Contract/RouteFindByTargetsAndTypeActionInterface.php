<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

interface RouteFindByTargetsAndTypeActionInterface
{
    public function find(RouteFindByTargetsAndTypeCriteria $criteria): ?RouteFindByTargetsAndTypeResult;
}
