<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Psr\SimpleCache\CacheInterface;

/**
 * Defines the access of the portal storage.
 * It can and will be used for caching.
 */
interface PortalStorageInterface extends CacheInterface
{
    /**
     * List all known keys in the storage.
     */
    public function list(): iterable;

    /**
     * Verifies that serialization type is supported by the storage for reading.
     */
    public function canGet(string $type): bool;

    /**
     * Verifies that serialization type is supported by the storage for writing.
     */
    public function canSet(string $type): bool;
}
