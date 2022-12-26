<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailLogError;

use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachmentAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Dataset\Base\Support\AttachmentAwareTrait;

/**
 * @extends AbstractObjectCollection<UiAuditTrailLogErrorPayload>
 */
final class UiAuditTrailLogErrorPayloadCollection extends AbstractObjectCollection implements AttachmentAwareInterface
{
    use AttachmentAwareTrait;

    /**
     * @param iterable<UiAuditTrailLogErrorPayload> $items
     */
    public function __construct(iterable $items = [])
    {
        parent::__construct($items);
        $this->attachments = new AttachmentCollection();
    }

    /**
     * @psalm-return UiAuditTrailLogErrorPayload::class
     */
    protected function getT(): string
    {
        return UiAuditTrailLogErrorPayload::class;
    }
}
