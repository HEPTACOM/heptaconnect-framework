<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class RouteAddPayload implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @param string[] $capabilities
     */
    public function __construct(
        private PortalNodeKeyInterface $sourcePortalNodeKey,
        private PortalNodeKeyInterface $targetPortalNodeKey,
        private EntityType $entityType,
        private array $capabilities = []
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

    public function getAuditableData(): array
    {
        return [
            'sourcePortalNode' => $this->getSourcePortalNodeKey(),
            'targetPortalNode' => $this->getTargetPortalNodeKey(),
            'entityType' => $this->getEntityType(),
            'capabilities' => $this->getCapabilities(),
        ];
    }
}
