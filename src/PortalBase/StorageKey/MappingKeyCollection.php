<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StorageKey;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingKeyInterface;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection<\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingKeyInterface>
 */
class MappingKeyCollection extends AbstractCollection
{
    protected function isValidItem($item): bool
    {
        /* @phpstan-ignore-next-line treatPhpDocTypesAsCertain checks soft check but this is the hard check */
        return $item instanceof MappingKeyInterface;
    }
}
