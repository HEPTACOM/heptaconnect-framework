<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection\Scalar;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;

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

    #[\Override]
    protected function isValidItem(mixed $item): bool
    {
        return \is_float($item);
    }
}
