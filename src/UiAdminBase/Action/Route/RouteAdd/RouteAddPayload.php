<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class RouteAddPayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    protected PortalNodeKeyInterface $sourcePortalNodeKey;

    protected PortalNodeKeyInterface $targetPortalNodeKey;

    protected EntityType $entityType;

    /**
     * @var string[]
     */
    protected array $capabilities;

    /**
     * @param string[] $capabilities
     */
    public function __construct(
        PortalNodeKeyInterface $sourcePortalNodeKey,
        PortalNodeKeyInterface $targetPortalNodeKey,
        EntityType $entityType,
        array $capabilities = []
    ) {
        $this->attachments = new AttachmentCollection();
        $this->sourcePortalNodeKey = $sourcePortalNodeKey;
        $this->targetPortalNodeKey = $targetPortalNodeKey;
        $this->entityType = $entityType;
        $this->capabilities = $capabilities;
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

    /**
     * @return string[]
     */
    public function getCapabilities(): array
    {
        return $this->capabilities;
    }

    /**
     * @param string[] $capabilities
     */
    public function setCapabilities(array $capabilities): void
    {
        $this->capabilities = $capabilities;
    }
}
