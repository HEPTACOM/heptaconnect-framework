<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Token;

use Heptacom\HeptaConnect\Dataset\Base\EntityType;

class ReceiverToken
{
    private EntityType $entityType;

    private ?\Closure $batch = null;

    private ?\Closure $run = null;

    public function __construct(EntityType $entityType)
    {
        $this->entityType = $entityType;
    }

    public function getEntityType(): EntityType
    {
        return $this->entityType;
    }

    public function getBatch(): ?\Closure
    {
        return $this->batch;
    }

    public function setBatch(?\Closure $batch): void
    {
        $this->batch = $batch;
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
