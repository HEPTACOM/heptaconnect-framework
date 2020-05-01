<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

/**
 * @extends GenericTranslatable<string>
 */
class TranslatableString extends GenericTranslatable
{
    protected function isValidValue($value): bool
    {
        return \is_string($value);
    }
}
