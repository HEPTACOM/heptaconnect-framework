<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;

final class PreviewPortalNodeKey implements PortalNodeKeyInterface
{
    /**
     * @var class-string<PortalContract>
     */
    private string $portalType;

    /**
     * @param class-string<PortalContract> $portalType
     */
    public function __construct(string $portalType)
    {
        $this->portalType = $portalType;
    }

    /**
     * @return class-string<PortalContract>
     */
    public function getPortalType(): string
    {
        return $this->portalType;
    }

    public function equals(StorageKeyInterface $other): bool
    {
        return $other === $this || ($other instanceof PreviewPortalNodeKey && $other->getPortalType() === $this->getPortalType());
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
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
