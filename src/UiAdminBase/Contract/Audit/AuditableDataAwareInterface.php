<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Audit;

interface AuditableDataAwareInterface
{
    /**
     * Returns auditable data, that is JSON serializable.
     */
    public function getAuditableData(): array;
}
