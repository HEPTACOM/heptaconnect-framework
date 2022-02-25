<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Token;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

final class ReceiverToken
{
    /**
     * @var class-string<DatasetEntityContract>
     */
    private string $type;

    private ?\Closure $batch = null;

    private ?\Closure $run = null;

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
