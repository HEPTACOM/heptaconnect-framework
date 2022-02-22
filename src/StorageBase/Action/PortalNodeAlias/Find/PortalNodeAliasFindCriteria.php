<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Find;

class PortalNodeAliasFindCriteria
{
    private array $alias;

    public function __construct(array $alias)
    {
        $this->alias = $alias;
    }

    public function getAlias(): array
    {
        return $this->alias;
    }

    public function setAlias(array $alias): void
    {
        $this->alias = $alias;
    }
}
