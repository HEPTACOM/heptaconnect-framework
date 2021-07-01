<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

class StatusReporterBuilder
{
    private StatusReporterToken $token;

    public function __construct(StatusReporterToken $token)
    {
        $this->token = $token;
    }

    public function run(callable $run): self
    {
        $this->token->setRun($run);

        return $this;
    }
}
