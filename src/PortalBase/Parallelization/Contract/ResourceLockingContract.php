<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Parallelization\Contract;

use Heptacom\HeptaConnect\Portal\Base\Parallelization\Exception\ResourceIsLockedException;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;

/**
 * Utility to use mutual exclusive resource accesses.
 * This can be used to limit file access to a single process.
 * To simplify relation to anything in the storage a storage key can be used as resource owner to influence resource key namespacing.
 * Resources can be unlocked by various reasons e.g. by time automatically.
 *
 * Resource locking is currently not working properly
 * as locks are released prematurely. This depends on
 * the underlying storage implementation. Currently
 * it is unclear whether a fix will require breaking
 * changes to the public api.
 *
 * @internal
 */
abstract class ResourceLockingContract
{
    /**
     * Returns true, when the non-blocking check verifies the named resource key is not locked.
     */
    abstract public function isLocked(string $resourceKey, ?StorageKeyInterface $owner): bool;

    /**
     * Locks the named resource key and throws when the resource is already locked.
     *
     * @throws ResourceIsLockedException
     */
    abstract public function lock(string $resourceKey, ?StorageKeyInterface $owner): void;

    /**
     * Unlocks the given resource key.
     */
    abstract public function release(string $resourceKey, ?StorageKeyInterface $owner): void;
}
