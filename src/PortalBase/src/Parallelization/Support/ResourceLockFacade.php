<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Parallelization\Support;

use Heptacom\HeptaConnect\Portal\Base\Parallelization\Contract\ResourceLockingContract;
use Heptacom\HeptaConnect\Portal\Base\Parallelization\Exception\ResourceIsLockedException;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;

class ResourceLockFacade
{
    private ResourceLockingContract $resourceLocking;

    public function __construct(ResourceLockingContract $resourceLocking)
    {
        $this->resourceLocking = $resourceLocking;
    }

    public function waitUntil(
        string $resourceKey,
        ?StorageKeyInterface $owner,
        int $iterations = 0,
        int $secondToWaits = 1
    ): bool {
        $countIterations = $iterations > 0;
        $isLocked = false;

        while (
            ($isLocked = $this->resourceLocking->isLocked($resourceKey, $owner)) &&
            (!$countIterations || $iterations-- > 0)
        ) {
            \sleep($secondToWaits);
        }

        return !$isLocked;
    }

    public function doLocked(
        callable $callable,
        string $resourceKey,
        ?StorageKeyInterface $owner
    ): void {
        try {
            $this->resourceLocking->lock($resourceKey, $owner);
            $callable();
        } finally {
            $this->resourceLocking->release($resourceKey, $owner);
        }
    }

    public function waitAndDoLocked(
        callable $callable,
        string $resourceKey,
        ?StorageKeyInterface $owner,
        int $iterations = 0,
        int $secondToWaits = 1
    ): void {
        if (!$this->waitUntil($resourceKey, $owner, $iterations, $secondToWaits)) {
            throw new ResourceIsLockedException($resourceKey, $owner);
        }

        $this->doLocked($callable, $resourceKey, $owner);
    }
}
