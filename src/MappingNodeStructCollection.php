<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\MappingNodeStructInterface;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection<\Heptacom\HeptaConnect\Storage\Base\Contract\MappingNodeStructInterface>
 */
class MappingNodeStructCollection extends DatasetEntityCollection
{
    protected function getT(): string
    {
        return MappingNodeStructInterface::class;
    }
}
