<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

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
