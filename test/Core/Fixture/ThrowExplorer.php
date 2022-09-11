<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;

final class ThrowExplorer extends ExplorerContract
{
    public function explore(ExploreContextInterface $context, ExplorerStackInterface $stack): iterable
    {
        throw new \RuntimeException();
    }

    public function supports(): string
    {
        return FooBarEntity::class;
    }
}
