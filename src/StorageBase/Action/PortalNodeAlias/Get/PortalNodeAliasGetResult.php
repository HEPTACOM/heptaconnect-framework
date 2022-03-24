<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeAlias\Get;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class PortalNodeAliasGetResult
{
    private PortalNodeKeyInterface $key;

    private string $alias;

    public function __construct(PortalNodeKeyInterface $key, string $alias)
    {
        $this->key = $key;
        $this->alias = $alias;
    }

    public function getKey(): PortalNodeKeyInterface
    {
        return $this->key;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
}
