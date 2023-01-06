<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Portal\Base\Portal\PortalType;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;

final class PreviewPortalNodeKey implements PortalNodeKeyInterface
{
    public function __construct(
        private PortalType $portalType
    ) {
    }

    public function getPortalType(): PortalType
    {
        return $this->portalType;
    }

    public function equals(StorageKeyInterface $other): bool
    {
        return $other === $this || ($other instanceof PreviewPortalNodeKey && $other->getPortalType()->equals($this->getPortalType()));
    }

    public function jsonSerialize(): array
    {
        return ['preview' => $this->portalType];
    }

    public function withAlias(): PortalNodeKeyInterface
    {
        return $this;
    }

    public function withoutAlias(): PortalNodeKeyInterface
    {
        return $this;
    }
}
