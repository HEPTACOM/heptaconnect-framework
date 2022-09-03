<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StorageKey;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;

/**
 * @extends AbstractCollection<MappingNodeKeyInterface>
 */
class MappingNodeKeyCollection extends AbstractCollection
{
    public function contains($value): bool
    {
        return $this->containsByEqualsCheck(
            $value,
            static fn (StorageKeyInterface $a, StorageKeyInterface $b): bool => $a->equals($b)
        );
    }

    protected function isValidItem($item): bool
    {
        /* @phpstan-ignore-next-line treatPhpDocTypesAsCertain checks soft check but this is the hard check */
        return $item instanceof MappingNodeKeyInterface;
    }
}
