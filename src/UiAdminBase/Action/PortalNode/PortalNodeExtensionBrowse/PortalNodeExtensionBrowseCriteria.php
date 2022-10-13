<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action\BrowseCriteriaContract;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class PortalNodeExtensionBrowseCriteria extends BrowseCriteriaContract implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    private bool $showActive = true;

    private bool $showInactive = true;

    public function __construct(PortalNodeKeyInterface $portalNodeKey)
    {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getShowActive(): bool
    {
        return $this->showActive;
    }

    public function setShowActive(bool $showActive): void
    {
        $this->showActive = $showActive;
    }

    public function getShowInactive(): bool
    {
        return $this->showInactive;
    }

    public function setShowInactive(bool $showInactive): void
    {
        $this->showInactive = $showInactive;
    }

    public function getAuditableData(): array
    {
        return [
            'portalNode' => $this->getPortalNodeKey(),
            'showActive' => $this->getShowActive(),
            'showInactive' => $this->getShowInactive(),
        ];
    }
}
