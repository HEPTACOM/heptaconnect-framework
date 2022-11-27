<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteAdd;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class RouteAddResult implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @param string[] $capabilities
     */
    public function __construct(
        private RouteKeyInterface $routeKey,
        private PortalNodeKeyInterface $sourcePortalNodeKey,
        private PortalNodeKeyInterface $targetPortalNodeKey,
        private ClassStringReferenceContract $entityType,
        private array $capabilities
    ) {
        $this->attachments = new AttachmentCollection();
    }

    public function getRouteKey(): RouteKeyInterface
    {
        return $this->routeKey;
    }

    public function getSourcePortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->sourcePortalNodeKey;
    }

    public function getTargetPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->targetPortalNodeKey;
    }

    public function getEntityType(): ClassStringReferenceContract
    {
        return $this->entityType;
    }

    /**
     * @return string[]
     */
    public function getCapabilities(): array
    {
        return $this->capabilities;
    }

    public function getAuditableData(): array
    {
        return [
            'route' => $this->getRouteKey(),
            'sourcePortalNode' => $this->getSourcePortalNodeKey(),
            'targetPortalNode' => $this->getTargetPortalNodeKey(),
            'entityType' => $this->getEntityType(),
            'capabilities' => $this->getCapabilities(),
        ];
    }
}
