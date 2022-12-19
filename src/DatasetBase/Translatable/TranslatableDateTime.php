<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Translatable;

/**
 * @extends AbstractTranslatable<\DateTimeInterface>
 */
final class TranslatableDateTime extends AbstractTranslatable
{
    protected function isValidValue(mixed $value): bool
    {
        /* @phpstan-ignore-next-line treatPhpDocTypesAsCertain checks soft check but this is the hard check */
        return $value instanceof \DateTimeInterface;
    }
}
