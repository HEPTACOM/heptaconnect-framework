<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Profiling;

abstract class ProfilerContract
{
    public function start(string $name, ?string $kind = null): void
    {
    }

    public function stop(?\Throwable $exception = null): void
    {
    }

    public function stopAndIgnore(): void
    {
    }
}
