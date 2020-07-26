<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;

interface ExplorerInterface
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    public function explore(ExploreContextInterface $context, ExplorerStackInterface $stack): iterable;

    /**
     * @return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    public function supports(): string;
}
