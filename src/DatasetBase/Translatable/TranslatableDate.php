<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

use Heptacom\HeptaConnect\Dataset\Base\Date;

/**
 * @extends AbstractTranslatable<Date>
 */
final class TranslatableDate extends AbstractTranslatable
{
    protected function isValidValue(mixed $value): bool
    {
        return $value instanceof Date;
    }
}
