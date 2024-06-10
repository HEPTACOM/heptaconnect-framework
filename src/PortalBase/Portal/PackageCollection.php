<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<PackageContract>
 */
class PackageCollection extends AbstractObjectCollection
{
    #[\Override]
    protected function getT(): string
    {
        return PackageContract::class;
    }
}
