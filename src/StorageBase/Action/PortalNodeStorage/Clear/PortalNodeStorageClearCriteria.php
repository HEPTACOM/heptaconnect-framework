<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeStorage\Clear;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;

final class PortalNodeStorageClearCriteria implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private PortalNodeKeyInterface $portalNodeKey
    ) {
        $this->attachments = new AttachmentCollection();
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }
}
