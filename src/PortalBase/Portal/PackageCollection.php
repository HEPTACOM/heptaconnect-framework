<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract;

/**
 * @extends AbstractObjectCollection<PackageContract>
 */
class PackageCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return PackageContract::class;
    }
}
