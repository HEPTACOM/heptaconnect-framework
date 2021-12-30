<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\Get;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class PortalNodeGetResult
{
    protected PortalNodeKeyInterface $portalNodeKey;

    /**
     * @var class-string<PortalContract>
     */
    protected string $portalClass;

    /**
     * @param class-string<PortalContract> $portalClass
     */
    public function __construct(PortalNodeKeyInterface $portalNodeKey, string $portalClass)
    {
        $this->portalNodeKey = $portalNodeKey;
        $this->portalClass = $portalClass;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    /**
     * @return class-string<PortalContract>
     */
    public function getPortalClass(): string
    {
        return $this->portalClass;
    }
}
