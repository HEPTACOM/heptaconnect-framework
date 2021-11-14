<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Token;

use Closure;

class EmitterToken
{
    private string $type;

    private ?Closure $batch = null;

    private ?Closure $run = null;

    private ?Closure $extend = null;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getBatch(): ?Closure
    {
        return $this->batch;
    }

    public function setBatch(?Closure $batch): void
    {
        $this->batch = $batch;
    }

    public function getRun(): ?Closure
    {
        return $this->run;
    }

    public function setRun(Closure $run): void
    {
        $this->run = $run;
    }

    public function getExtend(): ?Closure
    {
        return $this->extend;
    }

    public function setExtend(Closure $extend): void
    {
        $this->extend = $extend;
    }
}
