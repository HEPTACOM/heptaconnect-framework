<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Builder\Support;

trait BuilderPriorityTrait
{
    public function priority(int $priority): self
    {
        $this->token->setPriority($priority);

        return $this;
    }
}
