<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

interface ExplorerInterface
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    public function explore(ExploreContextInterface $context): iterable;

    public function supports(): string;
}
