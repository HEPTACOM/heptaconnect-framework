<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

/**
 * @extends AbstractTranslatable<string>
 */
final class TranslatableString extends AbstractTranslatable
{
    protected function isValidValue(mixed $value): bool
    {
        return \is_string($value);
    }
}
