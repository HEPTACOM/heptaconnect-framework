<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\IdentityRedirect\Create;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

final class IdentityRedirectCreatePayload implements CreatePayloadInterface, AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private PortalNodeKeyInterface $sourcePortalNodeKey,
        private string $sourceExternalId,
        private PortalNodeKeyInterface $targetPortalNodeKey,
        private string $targetExternalId,
        private EntityType $entityType,
    ) {
    }

    public function getSourcePortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->sourcePortalNodeKey;
    }

    public function setSourcePortalNodeKey(PortalNodeKeyInterface $sourcePortalNodeKey): void
    {
        $this->sourcePortalNodeKey = $sourcePortalNodeKey;
    }

    public function getTargetPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->targetPortalNodeKey;
    }

    public function setTargetPortalNodeKey(PortalNodeKeyInterface $targetPortalNodeKey): void
    {
        $this->targetPortalNodeKey = $targetPortalNodeKey;
    }

    public function getEntityType(): EntityType
    {
        return $this->entityType;
    }

    public function setEntityType(EntityType $entityType): void
    {
        $this->entityType = $entityType;
    }

    public function getSourceExternalId(): string
    {
        return $this->sourceExternalId;
    }

    public function setSourceExternalId(string $sourceExternalId): void
    {
        $this->sourceExternalId = $sourceExternalId;
    }

    public function getTargetExternalId(): string
    {
        return $this->targetExternalId;
    }

    public function setTargetExternalId(string $targetExternalId): void
    {
        $this->targetExternalId = $targetExternalId;
    }
}
