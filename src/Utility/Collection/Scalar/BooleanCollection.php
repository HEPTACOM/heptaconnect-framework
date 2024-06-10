<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection\Scalar;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<bool>
 */
final class BooleanCollection extends AbstractCollection
{
    #[\Override]
    protected function isValidItem(mixed $item): bool
    {
        return \is_bool($item);
    }
}
