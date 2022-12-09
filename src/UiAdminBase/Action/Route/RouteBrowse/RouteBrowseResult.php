<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\Route\RouteBrowse;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class RouteBrowseResult implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private RouteKeyInterface $routeKey,
        private PortalNodeKeyInterface $sourcePortalNodeKey,
        private PortalNodeKeyInterface $targetPortalNodeKey,
        private ClassStringReferenceContract $entityType,
        private StringCollection $capabilities
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

    public function getCapabilities(): StringCollection
    {
        return $this->capabilities;
    }

    public function getAuditableData(): array
    {
        return [
            'capabilities' => $this->getCapabilities(),
            'entityType' => $this->getEntityType(),
            'routeKey' => $this->getRouteKey(),
            'sourcePortalNodeKey' => $this->getSourcePortalNodeKey(),
            'targetPortalNodeKey' => $this->getTargetPortalNodeKey(),
        ];
    }
}
