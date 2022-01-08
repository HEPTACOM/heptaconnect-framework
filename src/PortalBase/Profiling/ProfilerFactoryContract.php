<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Profiling;

abstract class ProfilerFactoryContract
{
    abstract public function factory(?string $prefix = null): ProfilerContract;
}
