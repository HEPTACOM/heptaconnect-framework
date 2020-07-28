<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

abstract class ExplorerContract
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    abstract public function explore(ExploreContextInterface $context, ExplorerStackInterface $stack): iterable;

    /**
     * @return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     */
    abstract public function supports(): string;
}
