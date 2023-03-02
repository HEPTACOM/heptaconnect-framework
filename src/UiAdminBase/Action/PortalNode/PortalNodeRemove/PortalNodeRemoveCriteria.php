<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeRemove;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class PortalNodeRemoveCriteria implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private PortalNodeKeyCollection $portalNodeKeys
    ) {
    }

    public function getPortalNodeKeys(): PortalNodeKeyCollection
    {
        return $this->portalNodeKeys;
    }

    public function setPortalNodeKeys(PortalNodeKeyCollection $portalNodeKeys): void
    {
        $this->portalNodeKeys = $portalNodeKeys;
    }

    public function getAuditableData(): array
    {
        return [
            'portalNodeKeys' => $this->getPortalNodeKeys(),
        ];
    }
}
