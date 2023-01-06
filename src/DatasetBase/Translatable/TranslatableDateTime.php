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
        return $value instanceof \DateTimeInterface;
    }
}
