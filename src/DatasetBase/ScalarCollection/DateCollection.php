<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\ScalarCollection;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Date\Date;

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
