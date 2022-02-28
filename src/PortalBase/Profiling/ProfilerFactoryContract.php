<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Profiling;

abstract class ProfilerFactoryContract
{
    /**
     * Return a new profiler instance.
     * The prefix must influence measurement naming results for a clear namespacing of measurements.
     */
    abstract public function factory(?string $prefix = null): ProfilerContract;
}
