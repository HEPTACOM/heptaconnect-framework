<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Action;

use Heptacom\HeptaConnect\Ui\Admin\Base\Audit\UiAuditContext;

interface UiActionContextFactoryInterface
{
    /**
     * Creates an admin UI action call context.
     */
    public function createContext(UiAuditContext $auditContext): UiActionContextInterface;
}
