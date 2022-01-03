<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;

class PreviewPortalNodeKey implements PortalNodeKeyInterface
{
    /**
     * @var class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    private string $portalType;

    /**
     * @param class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract> $portalType
     */
    public function __construct(string $portalType)
    {
        $this->portalType = $portalType;
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract>
     */
    public function getPortalType(): string
    {
        return $this->portalType;
    }

    public function equals(StorageKeyInterface $other): bool
    {
        return $other === $this || ($other instanceof PreviewPortalNodeKey && $other->getPortalType() === $this->getPortalType());
    }

    public function jsonSerialize()
    {
        return ['preview' => $this->portalType];
    }
}
