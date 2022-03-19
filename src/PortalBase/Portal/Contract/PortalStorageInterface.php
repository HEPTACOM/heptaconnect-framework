<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Psr\SimpleCache\CacheInterface;

/**
 * Defines the access of the portal storage.
 * It can be used for caching.
 */
interface PortalStorageInterface extends CacheInterface
{
    /**
     * List all known keys in the storage.
     */
    public function list(): iterable;
}
