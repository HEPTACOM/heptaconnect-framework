<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

/**
 * @extends GenericTranslatable<float>
 */
class TranslatableFloat extends GenericTranslatable
{
    protected function isValidValue($value): bool
    {
        return \is_float($value);
    }
}
