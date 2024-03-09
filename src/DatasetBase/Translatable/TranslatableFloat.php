<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

/**
 * @extends AbstractTranslatable<float>
 */
final class TranslatableFloat extends AbstractTranslatable
{
    protected function isValidValue(mixed $value): bool
    {
        return \is_float($value);
    }
}
