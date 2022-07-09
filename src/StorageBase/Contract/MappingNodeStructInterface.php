<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;

interface MappingNodeStructInterface
{
    public function getKey(): MappingNodeKeyInterface;

    public function getEntityType(): EntityType;
}
