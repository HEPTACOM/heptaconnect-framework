<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Token;

class StatusReporterToken
{
    private ?\Closure $run = null;

    public function __construct(private string $topic)
    {
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function getRun(): ?\Closure
    {
        return $this->run;
    }

    public function setRun(\Closure $run): void
    {
        $this->run = $run;
    }
}
