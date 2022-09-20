<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\UiAuditTrail;

use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailEnd\UiAuditTrailEndPayload;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface UiAuditTrailEndActionInterface
{
    /**
     * Marks the audit trail as finished.
     *
     * @throws CreateException
     * @throws UnsupportedStorageKeyException
     */
    public function end(UiAuditTrailEndPayload $payload): void;
}
