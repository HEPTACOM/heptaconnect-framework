<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;

/**
 * @extends SubtypeClassStringContract<DatasetEntityContract>
 *
 * @phpstan-method class-string<DatasetEntityContract> __toString()
 * @phpstan-method class-string<DatasetEntityContract> jsonSerialize()
 */
final class EntityType extends SubtypeClassStringContract
{
    #[\Override]
    public function getExpectedSuperClassName(): string
    {
        return DatasetEntityContract::class;
    }
}
