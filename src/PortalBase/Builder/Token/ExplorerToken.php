<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Token;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;

class ExplorerToken
{
    private ?\Closure $run = null;

    private ?\Closure $isAllowed = null;

    public function __construct(
        private EntityType $entityType
    ) {
    }

    public function getEntityType(): EntityType
    {
        return $this->entityType;
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
