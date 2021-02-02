<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test\Fixture;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract as T;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;

/**
 * @extends DatasetEntityCollection<T>
 */
class UsageStructCollection extends DatasetEntityCollection
{
    protected function getT(): string
    {
        return T::class;
    }
}
