<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Find;

class PortalNodeAliasFindCriteria
{
    /**
     * @var string[]
     */
    private array $alias;

    /**
     * @param string[] $alias
     */
    public function __construct(array $alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string[]
     */
    public function getAlias(): array
    {
        return $this->alias;
    }

    /**
     * @param string[] $alias
     */
    public function setAlias(array $alias): void
    {
        $this->alias = $alias;
    }
}
