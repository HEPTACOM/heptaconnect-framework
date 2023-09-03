<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Builder;

use Heptacom\HeptaConnect\Portal\Base\Builder\Support\BuilderPriorityTrait;
use Heptacom\HeptaConnect\Portal\Base\Builder\Token\StatusReporterToken;

class StatusReporterBuilder
{
    use BuilderPriorityTrait;

    private StatusReporterToken $token;

    public function __construct(StatusReporterToken $token)
    {
        $this->token = $token;
    }

    public function run(\Closure $run): self
    {
        $this->token->setRun($run);

        return $this;
    }
}
