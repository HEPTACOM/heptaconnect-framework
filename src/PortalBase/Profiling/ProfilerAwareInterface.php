<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Profiling;

interface ProfilerAwareInterface
{
    /**
     * Sets the profiler used to profile.
     */
    public function setProfiler(ProfilerContract $profiler): void;
}
