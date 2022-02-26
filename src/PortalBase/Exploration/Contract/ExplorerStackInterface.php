<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

interface ExplorerStackInterface
{
    /**
     * @return iterable<array-key, DatasetEntityContract|string|int>
     */
    public function next(ExploreContextInterface $context): iterable;
}
