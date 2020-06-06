<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base;

use Heptacom\HeptaConnect\Portal\Base\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\ExplorerInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\ExplorerStackInterface;

class ExplorerStack implements ExplorerStackInterface
{
    /**
     * @var array<array-key, ExplorerInterface>
     */
    private array $explorers;

    /**
     * @param iterable<array-key, ExplorerInterface> $explorers
     */
    public function __construct(iterable $explorers)
    {
        $this->explorers = iterable_to_array($explorers);
    }

    public function next(ExploreContextInterface $context): iterable
    {
        $explorer = \array_shift($this->explorers);

        if (!$explorer instanceof ExplorerInterface) {
            return [];
        }

        return $explorer->explore($context, $this);
    }
}
