<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\IdentityRedirect\Overview;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\IdentityRedirectKeyInterface;

final class IdentityRedirectOverviewResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @param class-string<DatasetEntityContract> $entityType
     */
    public function __construct(
        private IdentityRedirectKeyInterface $identityRedirectKey,
        private PortalNodeKeyInterface $sourcePortalNodeKey,
        private string $sourceExternalId,
        private PortalNodeKeyInterface $targetPortalNodeKey,
        private string $targetExternalId,
        private string $entityType,
        private \DateTimeInterface $createdAt
    ) {
        $this->attachments = new AttachmentCollection();
    }

    public function getIdentityRedirectKey(): IdentityRedirectKeyInterface
    {
        return $this->identityRedirectKey;
    }

    public function getSourcePortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->sourcePortalNodeKey;
    }

    public function getSourceExternalId(): string
    {
        return $this->sourceExternalId;
    }

    public function getTargetPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->targetPortalNodeKey;
    }

    public function getTargetExternalId(): string
    {
        return $this->targetExternalId;
    }

    /**
     * @return class-string<DatasetEntityContract>
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
