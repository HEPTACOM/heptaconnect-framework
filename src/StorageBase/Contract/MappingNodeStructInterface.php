<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;

interface MappingNodeStructInterface
{
    public function getKey(): MappingNodeKeyInterface;

    /**
     * @return class-string<DatasetEntityContract>
     */
    public function getEntityType(): string;
}
