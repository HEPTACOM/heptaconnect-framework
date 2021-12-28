<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\MappingNodeStructInterface;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Storage\Base\Contract\MappingNodeStructInterface>
 */
class MappingNodeStructCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return MappingNodeStructInterface::class;
    }
}