<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Builder;

use Heptacom\HeptaConnect\Portal\Base\Builder\Token\ExplorerToken;

class ExplorerBuilder
{
    public function __construct(
        private ExplorerToken $token
    ) {
    }

    public function run(\Closure $run): self
    {
        $this->token->setRun($run);

        return $this;
    }

    public function isAllowed(\Closure $isAllowed): self
    {
        $this->token->setIsAllowed($isAllowed);

        return $this;
    }
}
