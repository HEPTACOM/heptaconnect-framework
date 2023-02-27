<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Action\PortalNode\PortalNodeStorageGet;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit\AuditableDataAwareInterface;

final class PortalNodeStorageGetResult implements AttachmentAwareInterface, AuditableDataAwareInterface
{
    use AttachmentAwareTrait;

    public function __construct(
        private PortalNodeKeyInterface $portalNodeKey,
        private string $storageKey,
        private string|int|float|bool|null $value
    ) {
    }

    public function getPortalNodeKey(): PortalNodeKeyInterface
    {
        return $this->portalNodeKey;
    }

    public function getStorageKey(): string
    {
        return $this->storageKey;
    }

    public function getValue(): string|int|float|bool|null
    {
        return $this->value;
    }

    public function getAuditableData(): array
    {
        return [
            'portalNodeKey' => $this->getPortalNodeKey(),
            'storageKey' => $this->getStorageKey(),
            'value' => $this->getValue(),
        ];
    }
}
