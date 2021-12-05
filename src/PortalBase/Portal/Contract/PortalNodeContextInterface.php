<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Portal\Base\Parallelization\Support\ResourceLockFacade;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Psr\Container\ContainerInterface;

interface PortalNodeContextInterface
{
    public function getConfig(): ?array;

    public function getPortal(): PortalContract;

    public function getPortalNodeKey(): PortalNodeKeyInterface;

    public function getResourceLocker(): ResourceLockFacade;

    public function getStorage(): PortalStorageInterface;

    public function getContainer(): ContainerInterface;
}
