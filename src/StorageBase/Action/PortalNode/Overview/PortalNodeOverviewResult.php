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

    public function __construct(
        private PortalNodeKeyInterface $portalNodeKey,
        private ClassStringReferenceContract $portalClass,
        private \DateTimeInterface $createdAt
    ) {
        $this->attachments = new AttachmentCollection();
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
