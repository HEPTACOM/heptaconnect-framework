<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder;

class ReceiverBuilder
{
    private ReceiverToken $token;

    public function __construct(ReceiverToken $token)
    {
        $this->token = $token;
    }

    public function run(callable $run): self
    {
        $this->token->setRun($run);

        return $this;
    }
}
