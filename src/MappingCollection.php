<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;

/**
 * @extends DatasetEntityCollection<Contract\MappingInterface>
 */
class MappingCollection extends DatasetEntityCollection
{
    protected function getT(): string
    {
        return Contract\MappingInterface::class;
    }
}
