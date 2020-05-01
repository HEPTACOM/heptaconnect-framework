<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DependencyInterface;

/**
 * @extends GenericTranslatable<DependencyInterface>
 */
class DependencyCollection extends DatasetEntityCollection
{
    use Support\DependencyTrait;

    protected function getT(): string
    {
        return DependencyInterface::class;
    }
}
