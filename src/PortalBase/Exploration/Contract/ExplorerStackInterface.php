<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

interface ExplorerStackInterface
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract|string|int>
     */
    public function next(ExploreContextInterface $context): iterable;
}
