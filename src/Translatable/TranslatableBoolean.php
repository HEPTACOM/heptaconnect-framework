<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

/**
 * @extends GenericTranslatable<bool>
 */
class TranslatableBoolean extends GenericTranslatable
{
    protected function isValidValue($value): bool
    {
        return \is_bool($value);
    }
}
