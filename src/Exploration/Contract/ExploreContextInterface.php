<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;

interface ExploreContextInterface
{
    public function getPortal(): ?PortalContract;

    /**
     * @psalm-return \ArrayAccess<array-key, mixed>|null
     */
    public function getConfig(): ?\ArrayAccess;
}
