<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Overview;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class PortalNodeOverviewResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

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
