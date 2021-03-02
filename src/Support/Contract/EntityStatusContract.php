<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Support\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

abstract class EntityStatusContract
{
    public abstract function isMappedByEmitter(DatasetEntityContract $entity): bool;
}
