<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Token;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

class ExplorerToken
{
    /**
     * @var class-string<DatasetEntityContract>
     */
    private string $type;

    private ?\Closure $run = null;

    private ?\Closure $isAllowed = null;

    /**
     * @param class-string<DatasetEntityContract> $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return class-string<DatasetEntityContract>
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function getRun(): ?\Closure
    {
        return $this->run;
    }

    public function setRun(?\Closure $run): void
    {
        $this->run = $run;
    }

    public function getIsAllowed(): ?\Closure
    {
        return $this->isAllowed;
    }

    public function setIsAllowed(?\Closure $isAllowed): void
    {
        $this->isAllowed = $isAllowed;
    }
}
