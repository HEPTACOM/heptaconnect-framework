<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\UiAuditTrail;

use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailLogError\UiAuditTrailLogErrorPayloadCollection;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface UiAuditTrailLogErrorActionInterface
{
    /**
     * Attaches an error message to the audit trail.
     *
     * @throws CreateException
     * @throws UnsupportedStorageKeyException
     */
    public function logError(UiAuditTrailLogErrorPayloadCollection $payloads): void;
}
