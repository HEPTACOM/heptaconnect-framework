<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\MappingPersister\Contract;

use Heptacom\HeptaConnect\Storage\Base\MappingPersistPayload;

abstract class MappingPersisterContract
{
    abstract public function persist(MappingPersistPayload $payload): void;
}
