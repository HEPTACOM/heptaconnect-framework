<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;

/**
 * @extends AbstractObjectCollection<MappedDatasetEntityStruct>
 */
class MappedDatasetEntityCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return MappedDatasetEntityStruct::class;
    }
}
