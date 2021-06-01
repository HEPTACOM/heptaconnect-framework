<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
 */
class DatasetEntityCollection extends AbstractObjectCollection
{
    protected function getT(): string
    {
        return DatasetEntityContract::class;
    }
}
