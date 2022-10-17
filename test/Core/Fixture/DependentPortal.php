<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;

final class DependentPortal extends PortalContract
{
    public function __construct(
        private ExplorerCollection $explorerCollection
    ) {
    }

    public function getExplorers(): ExplorerCollection
    {
        return $this->explorerCollection;
    }
}
