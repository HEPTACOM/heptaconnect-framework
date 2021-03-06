<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Support\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

abstract class EntityStatusContract
{
    /**
     * Checks the given entity whether it has been emitted with an identity.
     */
    abstract public function isMappedByEmitter(DatasetEntityContract $entity): bool;
}
