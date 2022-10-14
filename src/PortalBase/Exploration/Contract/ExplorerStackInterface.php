<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;

/**
 * Describes an ordered stack of explorers, that are grouped by their supported @see EntityType
 * As a stack the @see next execution reduces the list by one explorer.
 */
interface ExplorerStackInterface
{
    /**
     * Forwards the exploration to the next explorer on the stack and returns its response.
     *
     * @return iterable<array-key, DatasetEntityContract|string|int>
     */
    public function next(ExploreContextInterface $context): iterable;

    /**
     * Gets the supported entity type of the stack.
     */
    public function supports(): EntityType;
}
