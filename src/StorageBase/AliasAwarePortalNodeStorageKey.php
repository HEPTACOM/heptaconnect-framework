<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;

final class AliasAwarePortalNodeStorageKey implements PortalNodeKeyInterface
{
    private PortalNodeKeyInterface $portalNodeKey;

    public function __construct(PortalNodeKeyInterface $portalNodeKey)
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    public function withAlias(): PortalNodeKeyInterface
    {
        return $this;
    }

    public function withoutAlias(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function equals(StorageKeyInterface $other): bool
    {
        return $this->portalNodeKey->equals($other);
    }

    public function jsonSerialize()
    {
        return $this->portalNodeKey->jsonSerialize();
    }
}
