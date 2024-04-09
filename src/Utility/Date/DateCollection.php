<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Date;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<Date>
 */
final class DateCollection extends AbstractCollection
{
    protected function isValidItem(mixed $item): bool
    {
        return $item instanceof Date;
    }
}
