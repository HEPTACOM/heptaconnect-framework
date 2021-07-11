<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Builder;

use Heptacom\HeptaConnect\Portal\Base\Builder\Token\EmitterToken;

class EmitterBuilder
{
    private EmitterToken $token;

    public function __construct(EmitterToken $token)
    {
        $this->token = $token;
    }

    public function batch(callable $batch): self
    {
        $this->token->setBatch($batch);

        return $this;
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
