<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;

class DependentPortal extends PortalContract
{
    private ExplorerCollection $explorerCollection;

    public function __construct(ExplorerCollection $explorerCollection)
    {
        $this->explorerCollection = $explorerCollection;
    }

    public function getExplorers(): ExplorerCollection
    {
        return $this->explorerCollection;
    }
}
