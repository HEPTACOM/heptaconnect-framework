<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityStruct;

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
