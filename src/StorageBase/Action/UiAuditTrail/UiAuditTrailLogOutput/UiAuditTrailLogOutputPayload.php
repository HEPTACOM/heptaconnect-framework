<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailLogOutput;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;
use Heptacom\HeptaConnect\Dataset\Base\TaggedCollection\TaggedStringCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\UiAuditTrailKeyInterface;

final class UiAuditTrailLogOutputPayload implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    private UiAuditTrailKeyInterface $uiAuditTrailKey;

    private TaggedStringCollection $output;

    public function __construct(UiAuditTrailKeyInterface $uiAuditTrailKey, TaggedStringCollection $output)
    {
        $this->attachments = new AttachmentCollection();
        $this->uiAuditTrailKey = $uiAuditTrailKey;
        $this->output = $output;
    }

    public function getUiAuditTrailKey(): UiAuditTrailKeyInterface
    {
        return $this->uiAuditTrailKey;
    }

    public function setUiAuditTrailKey(UiAuditTrailKeyInterface $uiAuditTrailKey): void
    {
        $this->uiAuditTrailKey = $uiAuditTrailKey;
    }

    public function getOutput(): TaggedStringCollection
    {
        return $this->output;
    }

    public function setOutput(TaggedStringCollection $output): void
    {
        $this->output = $output;
    }
}
