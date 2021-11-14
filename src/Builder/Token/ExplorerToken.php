<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Token;

use Closure;

class ExplorerToken
{
    private string $type;

    private ?Closure $run = null;

    private ?Closure $isAllowed = null;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRun(): ?Closure
    {
        return $this->run;
    }

    public function setRun(?Closure $run): void
    {
        $this->run = $run;
    }

    public function getIsAllowed(): ?Closure
    {
        return $this->isAllowed;
    }

    public function setIsAllowed(?Closure $isAllowed): void
    {
        $this->isAllowed = $isAllowed;
    }
}
