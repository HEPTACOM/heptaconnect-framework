<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;

/**
 * @extends DatasetEntityCollection<MappedDatasetEntityStruct>
 */
class MappedDatasetEntityCollection extends DatasetEntityCollection
{
    protected function getT(): string
    {
        return MappedDatasetEntityStruct::class;
    }
}
