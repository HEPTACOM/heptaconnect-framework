<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Storage\Base\Contract\IdentityRedirectKeyInterface;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<IdentityRedirectKeyInterface>
 */
class IdentityRedirectKeyCollection extends AbstractCollection
{
    #[\Override]
    protected function isValidItem(mixed $item): bool
    {
        return $item instanceof IdentityRedirectKeyInterface;
    }
}
