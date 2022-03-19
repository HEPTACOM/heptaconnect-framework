<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Profiling;

/**
 * Describes a generic profiler that can start a profiling session, stop it and discard a previously started session.
 * Nesting of session must be supported.
 */
abstract class ProfilerContract
{
    /**
     * Start a profiling session with a given name.
     */
    public function start(string $name, ?string $kind = null): void
    {
    }

    /**
     * Stop profiling session and store measurement.
     * When an exception is given it is expected as failed.
     */
    public function stop(?\Throwable $exception = null): void
    {
    }

    /**
     * Stop profiling session and discard measurement.
     */
    public function stopAndIgnore(): void
    {
    }
}
