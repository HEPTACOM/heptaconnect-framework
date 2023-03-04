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

    private IdentityRedirectKeyInterface $identityRedirectKey;

    private PortalNodeKeyInterface $sourcePortalNodeKey;

    private string $sourceExternalId;

    private PortalNodeKeyInterface $targetPortalNodeKey;

    private string $targetExternalId;

    /**
     * @var class-string<DatasetEntityContract>
     */
    private string $entityType;

    private \DateTimeInterface $createdAt;

    /**
     * @param class-string<DatasetEntityContract> $entityType
     */
    public function __construct(
        IdentityRedirectKeyInterface $identityRedirectKey,
        PortalNodeKeyInterface $sourcePortalNodeKey,
        string $sourceExternalId,
        PortalNodeKeyInterface $targetPortalNodeKey,
        string $targetExternalId,
        string $entityType,
        \DateTimeInterface $createdAt
    ) {
        $this->attachments = new AttachmentCollection();
        $this->identityRedirectKey = $identityRedirectKey;
        $this->sourcePortalNodeKey = $sourcePortalNodeKey;
        $this->sourceExternalId = $sourceExternalId;
        $this->targetPortalNodeKey = $targetPortalNodeKey;
        $this->targetExternalId = $targetExternalId;
        $this->entityType = $entityType;
        $this->createdAt = $createdAt;
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
