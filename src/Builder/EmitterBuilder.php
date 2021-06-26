<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

class EmitterBuilder
{
    private EmitterToken $token;

    public function __construct(EmitterToken $token)
    {
        $this->token = $token;
    }

    public function run(callable $run): self
    {
        $this->token->setRun($run);

        return $this;
    }

    public function extend(callable $extend): self
    {
        $this->token->setExtend($extend);

        return $this;
    }
}
