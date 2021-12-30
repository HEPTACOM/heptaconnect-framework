<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\Overview;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

class PortalNodeOverviewResult
{
    protected PortalNodeKeyInterface $portalNodeKey;

    /**
     * @var class-string<PortalContract>
     */
    protected string $portalClass;

    protected \DateTimeInterface $createdAt;

    /**
     * @param class-string<PortalContract> $portalNodeKey
     */
    public function __construct(
        PortalNodeKeyInterface $portalNodeKey,
        string $portalClass,
        \DateTimeInterface $createdAt
    ) {
        $this->portalNodeKey = $portalNodeKey;
        $this->portalClass = $portalClass;
        $this->createdAt = $createdAt;
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

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
