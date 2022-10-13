<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\UiAuditTrail;

use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailLogOutput\UiAuditTrailLogOutputPayload;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface UiAuditTrailLogOutputActionInterface
{
    /**
     * Attaches an action output to the audit trail.
     *
     * @throws CreateException
     * @throws UnsupportedStorageKeyException
     */
    public function logOutput(UiAuditTrailLogOutputPayload $payload): void;
}
