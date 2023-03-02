<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStorageGet;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\ScalarCollection\StringCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class PortalNodeStorageGetCriteria implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private PortalNodeKeyInterface $portalNodeKey,
        private StringCollection $storageKeys
    ) {
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function setPortalNodeKey(PortalNodeKeyInterface $portalNodeKey): void
    {
        $this->portalNodeKey = $portalNodeKey;
    }

    public function getStorageKeys(): StringCollection
    {
        return $this->storageKeys;
    }

    public function setStorageKeys(StringCollection $storageKeys): void
    {
        $this->storageKeys = $storageKeys;
    }

    public function getAuditableData(): array
    {
        return [
            'portalNodeKey' => $this->getPortalNodeKey(),
            'storageKeys' => $this->getStorageKeys(),
        ];
    }
}
