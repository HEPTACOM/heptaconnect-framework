<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Token;

class EmitterToken
{
    private string $type;

    /** @var callable|null */
    private $batch = null;

    /** @var callable|null */
    private $run = null;

    /** @var callable|null */
    private $extend = null;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getBatch(): ?callable
    {
        return $this->batch;
    }

    public function setBatch(?callable $batch): void
    {
        $this->batch = $batch;
    }

    public function getRun(): ?callable
    {
        return $this->run;
    }

    public function setRun(callable $run): void
    {
        $this->run = $run;
    }

    public function getExtend(): ?callable
    {
        return $this->extend;
    }

    public function setExtend(callable $extend): void
    {
        $this->extend = $extend;
    }
}
