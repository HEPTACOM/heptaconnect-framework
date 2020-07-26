<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Mapping;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection<\Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct>
 */
class MappedDatasetEntityCollection extends DatasetEntityCollection
{
    protected function getT(): string
    {
        return MappedDatasetEntityStruct::class;
    }
}
