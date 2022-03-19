<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

final class EntityA extends DatasetEntityContract
{
    public string $value = '';
}
