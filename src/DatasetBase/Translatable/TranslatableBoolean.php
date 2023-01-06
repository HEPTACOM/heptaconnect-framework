<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

/**
 * @extends AbstractTranslatable<bool>
 */
final class TranslatableBoolean extends AbstractTranslatable
{
    protected function isValidValue(mixed $value): bool
    {
        return \is_bool($value);
    }
}
