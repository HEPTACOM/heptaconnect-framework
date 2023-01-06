<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\UiAuditTrailKeyInterface;

/**
 * @extends AbstractCollection<UiAuditTrailKeyInterface>
 */
final class UiAuditTrailKeyCollection extends AbstractCollection
{
    protected function isValidItem(mixed $item): bool
    {
        return $item instanceof UiAuditTrailKeyInterface;
    }
}
