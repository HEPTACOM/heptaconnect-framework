<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailEnd;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\Contract\UiAuditTrailKeyInterface;

final class UiAuditTrailEndPayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private \DateTimeImmutable $at;

    private UiAuditTrailKeyInterface $uiAuditTrailKey;

    public function __construct(UiAuditTrailKeyInterface $uiAuditTrailKey)
    {
        $this->attachments = new AttachmentCollection();
        $this->at = new \DateTimeImmutable();
        $this->uiAuditTrailKey = $uiAuditTrailKey;
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
