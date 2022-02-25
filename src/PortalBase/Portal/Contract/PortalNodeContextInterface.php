<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Portal\Base\Parallelization\Support\ResourceLockFacade;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Psr\Container\ContainerInterface;

/**
 * Describes any flow component context.
 */
interface PortalNodeContextInterface
{
    /**
     * Returns the configuration as plain array.
     */
    public function getConfig(): ?array;

    /**
     * Returns the instance of the portal of this stack.
     */
    public function getPortal(): PortalContract;

    /**
     * Returns the storage key of the portal node of this stack.
     */
    public function getPortalNodeKey(): PortalNodeKeyInterface;

    /**
     * Returns an instance for resource locking limited to the portal node of this stack.
     */
    public function getResourceLocker(): ResourceLockFacade;

    /**
     * Returns an instance for accessing storage limited to the portal node of this stack.
     */
    public function getStorage(): PortalStorageInterface;

    /**
     * Returns the container of the portal node of this stack.
     */
    public function getContainer(): ContainerInterface;
}
