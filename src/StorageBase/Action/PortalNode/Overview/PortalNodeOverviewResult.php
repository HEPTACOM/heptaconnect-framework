<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Overview;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class PortalNodeOverviewResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    protected PortalNodeKeyInterface $portalNodeKey;

    protected ClassStringReferenceContract $portalClass;

    protected \DateTimeInterface $createdAt;

    public function __construct(
        PortalNodeKeyInterface $portalNodeKey,
        ClassStringReferenceContract $portalClass,
        \DateTimeInterface $createdAt
    ) {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKey = $portalNodeKey;
        $this->portalClass = $portalClass;
        $this->createdAt = $createdAt;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getPortalClass(): ClassStringReferenceContract
    {
        return $this->portalClass;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
