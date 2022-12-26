<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailEnd;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\Contract\UiAuditTrailKeyInterface;

final class UiAuditTrailEndPayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private \DateTimeImmutable $at;

    public function __construct(
        private UiAuditTrailKeyInterface $uiAuditTrailKey
    ) {
        $this->at = new \DateTimeImmutable();
    }

    public function getAt(): \DateTimeImmutable
    {
        return $this->at;
    }

    public function setAt(\DateTimeImmutable $at): void
    {
        $this->at = $at;
    }

    public function getUiAuditTrailKey(): UiAuditTrailKeyInterface
    {
        return $this->uiAuditTrailKey;
    }

    public function setUiAuditTrailKey(UiAuditTrailKeyInterface $uiAuditTrailKey): void
    {
        $this->uiAuditTrailKey = $uiAuditTrailKey;
    }
}
