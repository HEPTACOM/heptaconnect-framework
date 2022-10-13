<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Action\UiAuditTrail;

use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailBegin\UiAuditTrailBeginPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\UiAuditTrail\UiAuditTrailBegin\UiAuditTrailBeginResult;
use Heptacom\HeptaConnect\Storage\Base\Exception\CreateException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

interface UiAuditTrailBeginActionInterface
{
    /**
     * Creates an audit trail for UI actions.
     *
     * @throws CreateException
     * @throws UnsupportedStorageKeyException
     */
    public function begin(UiAuditTrailBeginPayload $payload): UiAuditTrailBeginResult;
}
