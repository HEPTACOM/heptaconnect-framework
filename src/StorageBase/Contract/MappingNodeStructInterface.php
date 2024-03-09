<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;

/**
 * Describes key features of a mapping node.
 */
interface MappingNodeStructInterface
{
    /**
     * Gets the key of the mapping node.
     */
    public function getKey(): MappingNodeKeyInterface;

    /**
     * Gets the entity type of the mapping node.
     */
    public function getEntityType(): EntityType;
}
