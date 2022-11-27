<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test\Fixture;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;

final class UsageStructCollection extends DatasetEntityCollection
{
    protected function getT(): string
    {
        return DatasetEntityContract::class;
    }
}
