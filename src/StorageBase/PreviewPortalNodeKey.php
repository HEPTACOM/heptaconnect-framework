<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Portal\Base\Portal\PortalType;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;

final readonly class PreviewPortalNodeKey implements PortalNodeKeyInterface
{
    public function __construct(
        private PortalType $portalType
    ) {
    }

    public function getPortalType(): PortalType
    {
        return $this->portalType;
    }

    #[\Override]
    public function equals(StorageKeyInterface $other): bool
    {
        return $other === $this || ($other instanceof PreviewPortalNodeKey && $other->getPortalType()->equals($this->getPortalType()));
    }

    #[\Override]
    public function jsonSerialize(): array
    {
        return ['preview' => $this->portalType];
    }

    #[\Override]
    public function withAlias(): PortalNodeKeyInterface
    {
        return $this;
    }

    #[\Override]
    public function withoutAlias(): PortalNodeKeyInterface
    {
        return $this;
    }
}
