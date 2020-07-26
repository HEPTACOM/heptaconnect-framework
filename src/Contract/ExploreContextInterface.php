<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

use Heptacom\HeptaConnect\Portal\Base\Contract\PortalNodeInterface;

interface ExploreContextInterface
{
    public function getPortalNode(): PortalNodeInterface;

    /**
     * @psalm-return \ArrayAccess<array-key, mixed>|null
     */
    public function getConfig(): ?\ArrayAccess;
}
