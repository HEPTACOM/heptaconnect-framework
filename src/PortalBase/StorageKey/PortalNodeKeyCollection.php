<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StorageKey;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<PortalNodeKeyInterface>
 */
class PortalNodeKeyCollection extends AbstractCollection
{
    public function contains($value): bool
    {
        return $this->containsByEqualsCheck(
            $value,
            static fn (StorageKeyInterface $a, StorageKeyInterface $b): bool => $a->equals($b)
        );
    }

    protected function isValidItem(mixed $item): bool
    {
        return $item instanceof PortalNodeKeyInterface;
    }
}
