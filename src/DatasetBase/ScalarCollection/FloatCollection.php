<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\ScalarCollection;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection;

/**
 * @extends AbstractCollection<float>
 */
final class FloatCollection extends AbstractCollection
{
    public function min(): ?float
    {
        if ($this->isEmpty()) {
            return null;
        }

        return (float) \min($this->items);
    }

    public function max(): ?float
    {
        if ($this->isEmpty()) {
            return null;
        }

        return (float) \max($this->items);
    }

    public function sum(): float
    {
        if ($this->isEmpty()) {
            return 0.0;
        }

        return (float) \array_sum($this->items);
    }

    protected function isValidItem($item): bool
    {
        /* @phpstan-ignore-next-line treatPhpDocTypesAsCertain checks soft check but this is the hard check */
        return \is_float($item);
    }
}
