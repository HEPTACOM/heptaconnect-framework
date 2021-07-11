<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

class ExplorerToken
{
    private string $type;

    /** @var callable|null */
    private $run = null;

    /** @var callable|null */
    private $isAllowed = null;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRun(): ?callable
    {
        return $this->run;
    }

    public function setRun(?callable $run): void
    {
        $this->run = $run;
    }

    public function getIsAllowed(): ?callable
    {
        return $this->isAllowed;
    }

    public function setIsAllowed(?callable $isAllowed): void
    {
        $this->isAllowed = $isAllowed;
    }
}
