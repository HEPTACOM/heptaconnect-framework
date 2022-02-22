<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Overview;

class PortalNodeAliasOverviewResult
{
    private string $keyData;

    private string $alias;

    public function __construct(string $keyData, string $alias)
    {
        $this->keyData = $keyData;
        $this->alias = $alias;
    }

    public function getKeyData(): string
    {
        return $this->keyData;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
}
