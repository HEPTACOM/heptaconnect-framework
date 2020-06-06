<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

use Heptacom\HeptaConnect\Dataset\Base\Date;

/**
 * @extends GenericTranslatable<\Heptacom\HeptaConnect\Dataset\Base\Date>
 */
class TranslatableDate extends GenericTranslatable
{
    protected function isValidValue($value): bool
    {
        /* @phpstan-ignore-next-line treatPhpDocTypesAsCertain checks soft check but this is the hard check */
        return $value instanceof Date;
    }
}
