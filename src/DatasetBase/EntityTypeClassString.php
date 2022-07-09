<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract;

/**
 * @extends SubtypeClassStringContract<DatasetEntityContract>
 */
class EntityTypeClassString extends SubtypeClassStringContract
{
    public function getExpectedSuperClassName(): string
    {
        return DatasetEntityContract::class;
    }
}
