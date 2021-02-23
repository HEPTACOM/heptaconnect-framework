<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Parallelization\Contract;

use Heptacom\HeptaConnect\Portal\Base\Parallelization\Exception\ResourceIsLockedException;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;

abstract class ResourceLockingContract
{
    abstract public function isLocked(string $resourceKey, ?StorageKeyInterface $owner): bool;

    /**
     * @throws ResourceIsLockedException
     */
    abstract public function lock(string $resourceKey, ?StorageKeyInterface $owner): void;

    abstract public function release(string $resourceKey, ?StorageKeyInterface $owner): void;
}
