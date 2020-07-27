<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalInterface;

interface ExploreContextInterface
{
    public function getPortalNode(): ?PortalInterface;

    /**
     * @psalm-return \ArrayAccess<array-key, mixed>|null
     */
    public function getConfig(): ?\ArrayAccess;
}
