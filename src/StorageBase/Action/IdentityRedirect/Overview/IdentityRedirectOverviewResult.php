<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\IdentityRedirect\Overview;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\IdentityRedirectKeyInterface;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Utility\Attachment\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;

final class IdentityRedirectOverviewResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private IdentityRedirectKeyInterface $identityRedirectKey,
        private PortalNodeKeyInterface $sourcePortalNodeKey,
        private string $sourceExternalId,
        private PortalNodeKeyInterface $targetPortalNodeKey,
        private string $targetExternalId,
        private ClassStringReferenceContract $entityType,
        private \DateTimeInterface $createdAt
    ) {
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

    public function getEntityType(): ClassStringReferenceContract
    {
        return $this->entityType;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
