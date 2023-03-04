<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\IdentityRedirect\Create;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Create\CreatePayloadInterface;

final class IdentityRedirectCreatePayload implements CreatePayloadInterface, AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    protected PortalNodeKeyInterface $sourcePortalNodeKey;

    protected string $sourceExternalId;

    protected PortalNodeKeyInterface $targetPortalNodeKey;

    protected string $targetExternalId;

    /**
     * @var class-string<DatasetEntityContract>
     */
    protected string $entityType;

    /**
     * @param class-string<DatasetEntityContract> $entityType
     */
    public function __construct(
        PortalNodeKeyInterface $sourcePortalNodeKey,
        string $sourceExternalId,
        PortalNodeKeyInterface $targetPortalNodeKey,
        string $targetExternalId,
        string $entityType,
    ) {
        $this->attachments = new AttachmentCollection();
        $this->sourcePortalNodeKey = $sourcePortalNodeKey;
        $this->sourceExternalId = $sourceExternalId;
        $this->targetPortalNodeKey = $targetPortalNodeKey;
        $this->targetExternalId = $targetExternalId;
        $this->entityType = $entityType;
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

    /**
     * @return class-string<DatasetEntityContract>
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }

    /**
     * @param class-string<DatasetEntityContract> $entityType
     */
    public function setEntityType(string $entityType): void
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
