<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Builder;

use Heptacom\HeptaConnect\Portal\Base\Builder\Support\BuilderPriorityTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\ReceiverToken;

class ReceiverBuilder
{
    use BuilderPriorityTrait;

    private ReceiverToken $token;

    public function __construct(ReceiverToken $token)
    {
        $this->token = $token;
    }

    public function batch(\Closure $batch): self
    {
        $this->token->setBatch($batch);

        return $this;
    }

    public function run(\Closure $run): self
    {
        $this->token->setRun($run);

        return $this;
    }
}
