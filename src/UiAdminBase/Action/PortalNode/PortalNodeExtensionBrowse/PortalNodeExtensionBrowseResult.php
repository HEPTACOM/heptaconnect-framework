<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class PortalNodeExtensionBrowseResult implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    private bool $active;

    private ClassStringReferenceContract $portalExtensionType;

    public function __construct(
        PortalNodeKeyInterface $portalNodeKey,
        bool $active,
        ClassStringReferenceContract $portalExtensionType
    ) {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKey = $portalNodeKey;
        $this->active = $active;
        $this->portalExtensionType = $portalExtensionType;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function getPortalExtensionType(): ClassStringReferenceContract
    {
        return $this->portalExtensionType;
    }

    public function getAuditableData(): array
    {
        return [
            'portalNode' => $this->getPortalNodeKey(),
            'active' => $this->getActive(),
            'portalExtensionType' => $this->getPortalExtensionType(),
        ];
    }
}
