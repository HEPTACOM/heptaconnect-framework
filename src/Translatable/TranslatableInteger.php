<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

/**
 * @extends GenericTranslatable<int>
 */
class TranslatableInteger extends GenericTranslatable
{
    protected function isValidValue($value): bool
    {
        return \is_int($value);
    }
}
