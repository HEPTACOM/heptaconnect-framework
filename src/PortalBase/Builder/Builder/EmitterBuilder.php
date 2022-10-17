<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Builder;

use Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken;

class EmitterBuilder
{
    public function __construct(
        private EmitterToken $token
    ) {
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

    public function extend(\Closure $extend): self
    {
        $this->token->setExtend($extend);

        return $this;
    }
}
