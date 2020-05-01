<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

/**
 * @extends GenericTranslatable<\DateTimeInterface>
 */
class TranslatableDateTime extends GenericTranslatable
{
    protected function isValidValue($value): bool
    {
        return $value instanceof \DateTimeInterface;
    }
}
