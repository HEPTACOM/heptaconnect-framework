<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

/**
 * Resource locking is currently not working properly
 * as locks are released prematurely. This depends on
 * the underlying storage implementation. Currently
 * it is unclear whether a fix will require breaking
 * changes to the public api.
 *
 * @internal
 */
abstract class ResourceLockStorageContract
{
    abstract public function create(string $key): void;

    abstract public function has(string $key): bool;

    abstract public function delete(string $key): void;
}
