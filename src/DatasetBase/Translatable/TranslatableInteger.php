<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

/**
 * @extends AbstractTranslatable<int>
 */
final class TranslatableInteger extends AbstractTranslatable
{
    protected function isValidValue(mixed $value): bool
    {
        /* @phpstan-ignore-next-line treatPhpDocTypesAsCertain checks soft check but this is the hard check */
        return \is_int($value);
    }
}
