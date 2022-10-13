<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailBegin;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Storage\Base\Contract\UiAuditTrailKeyInterface;

final class UiAuditTrailBeginResult implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private UiAuditTrailKeyInterface $uiAuditTrailKey;

    public function __construct(UiAuditTrailKeyInterface $uiAuditTrailKey)
    {
        $this->attachments = new AttachmentCollection();
        $this->uiAuditTrailKey = $uiAuditTrailKey;
    }

    public function getUiAuditTrailKey(): UiAuditTrailKeyInterface
    {
        return $this->uiAuditTrailKey;
    }
}
