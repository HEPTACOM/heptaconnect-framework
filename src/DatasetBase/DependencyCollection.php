<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DependencyInterface;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Dataset\Base\Contract\DependencyInterface>
 */
class DependencyCollection extends AbstractObjectCollection
{
    use Support\DependencyTrait;

    protected function getT(): string
    {
        return DependencyInterface::class;
    }
}
