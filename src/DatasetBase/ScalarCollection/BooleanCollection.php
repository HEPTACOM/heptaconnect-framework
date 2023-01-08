<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\ScalarCollection;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection;

/**
 * @extends AbstractCollection<bool>
 */
final class BooleanCollection extends AbstractCollection
{
    protected function isValidItem(mixed $item): bool
    {
        return \is_bool($item);
    }
}
