<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Date;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<\DateTimeInterface>
 */
final class DateTimeCollection extends AbstractCollection
{
    #[\Override]
    protected function isValidItem(mixed $item): bool
    {
        return $item instanceof \DateTimeInterface;
    }
}
