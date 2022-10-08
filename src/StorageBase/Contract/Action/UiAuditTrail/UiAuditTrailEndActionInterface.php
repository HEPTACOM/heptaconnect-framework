<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\UiAuditTrail;

use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailEnd\UiAuditTrailEndPayload;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UpdateException;

interface UiAuditTrailEndActionInterface
{
    /**
     * Marks the audit trail as finished.
     *
     * @throws UpdateException
     * @throws UnsupportedStorageKeyException
     */
    public function end(UiAuditTrailEndPayload $payload): void;
}
