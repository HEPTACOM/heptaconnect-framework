<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\ClassStringReferenceCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

abstract class PortalNodeExtensionActiveChangePayloadContract implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    private ClassStringReferenceCollection $portalExtensionQueries;

    public function __construct(PortalNodeKeyInterface $portalNodeKey)
    {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKey = $portalNodeKey;
        $this->portalExtensionQueries = new ClassStringReferenceCollection();
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getPortalExtensionQueries(): ClassStringReferenceCollection
    {
        return $this->portalExtensionQueries;
    }

    public function setPortalExtensionQueries(ClassStringReferenceCollection $portalExtensionQueries): void
    {
        $this->portalExtensionQueries = $portalExtensionQueries;
    }

    public function getAuditableData(): array
    {
        return [
            'portalNode' => $this->getPortalNodeKey(),
            'portalExtensionQueries' => $this->getPortalExtensionQueries(),
        ];
    }
}
