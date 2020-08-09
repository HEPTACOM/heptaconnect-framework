<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface;

interface ExploreContextInterface
{
    public function getPortal(): ?PortalContract;

    public function getConfig(): ?array;

    public function getStorage(): PortalStorageInterface;
}
