<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

class ReceiverToken
{
    private string $type;

    /** @var callable|null */
    private $batch = null;

    /** @var callable|null */
    private $run = null;

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
}
