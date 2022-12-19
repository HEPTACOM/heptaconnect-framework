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
        /* @phpstan-ignore-next-line treatPhpDocTypesAsCertain checks soft check but this is the hard check */
        return $value instanceof Date;
    }
}
