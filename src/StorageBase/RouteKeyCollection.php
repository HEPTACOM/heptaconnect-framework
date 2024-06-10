<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\RouteKeyInterface;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<RouteKeyInterface>
 */
class RouteKeyCollection extends AbstractCollection
{
    #[\Override]
    public function contains($value): bool
    {
        return $this->containsByEqualsCheck(
            $value,
            static fn (StorageKeyInterface $a, StorageKeyInterface $b): bool => $a->equals($b)
        );
    }

    #[\Override]
    protected function isValidItem(mixed $item): bool
    {
        return $item instanceof RouteKeyInterface;
    }
}
