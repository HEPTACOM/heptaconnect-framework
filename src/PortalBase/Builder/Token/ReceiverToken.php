<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Token;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\EntityTypeClassString;

class ReceiverToken
{
    private EntityTypeClassString $entityType;

    private ?\Closure $batch = null;

    private ?\Closure $run = null;

    public function __construct(EntityTypeClassString $entityType)
    {
        $this->entityType = $entityType;
    }

    /**
     * @deprecated Use @see getEntityType instead
     *
     * @return class-string<DatasetEntityContract>
     */
    public function getType(): string
    {
        return $this->entityType->getClassString();
    }

    public function getEntityType(): EntityTypeClassString
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
