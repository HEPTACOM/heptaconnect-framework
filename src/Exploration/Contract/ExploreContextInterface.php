<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Exploration\Contract;

use Heptacom\HeptaConnect\Portal\Base\Parallelization\Support\ResourceLockFacade;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

interface ExploreContextInterface
{
    public function getPortalNodeKey(): PortalNodeKeyInterface;

    public function getPortal(): PortalContract;

    public function getConfig(): ?array;

    public function getStorage(): PortalStorageInterface;

    public function getResourceLocker(): ResourceLockFacade;
}
