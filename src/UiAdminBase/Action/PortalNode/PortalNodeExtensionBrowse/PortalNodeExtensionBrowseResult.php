<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeExtensionBrowse;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class PortalNodeExtensionBrowseResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private PortalNodeKeyInterface $portalNodeKey;

    private bool $active;

    /**
     * @var class-string<PortalExtensionContract>
     */
    private string $portalExtensionClass;

    /**
     * @param class-string<PortalExtensionContract> $portalExtensionClass
     */
    public function __construct(PortalNodeKeyInterface $portalNodeKey, bool $active, string $portalExtensionClass)
    {
        $this->attachments = new AttachmentCollection();
        $this->portalNodeKey = $portalNodeKey;
        $this->active = $active;
        $this->portalExtensionClass = $portalExtensionClass;
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * @return class-string<PortalExtensionContract>
     */
    public function getPortalExtensionClass(): string
    {
        return $this->portalExtensionClass;
    }
}
