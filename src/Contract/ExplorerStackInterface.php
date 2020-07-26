<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;

interface ExplorerStackInterface
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    public function next(ExploreContextInterface $context): iterable;
}
