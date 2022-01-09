<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\DependencyCollection;

trait DependencyAwareTrait
{
    protected DependencyCollection $dependencies;

    public function getDependencies(): DependencyCollection
    {
        return $this->dependencies;
    }
}
