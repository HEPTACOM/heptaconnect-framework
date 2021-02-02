<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Support;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;

/**
 * @extends \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
 */
class TrackedEntityCollection extends DatasetEntityCollection
{
    protected function getT(): string
    {
        return DatasetEntityContract::class;
    }
}
