<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\ScalarCollection;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection;

/**
 * @extends AbstractCollection<int>
 */
final class IntegerCollection extends AbstractCollection
{
    public function min(): ?int
    {
        if ($this->isEmpty()) {
            return null;
        }

        return (int) \min($this->items);
    }

    public function max(): ?int
    {
        if ($this->isEmpty()) {
            return null;
        }

        return (int) \max($this->items);
    }

    public function sum(): int
    {
        if ($this->isEmpty()) {
            return 0;
        }

        return (int) \array_sum($this->items);
    }

    protected function isValidItem($item): bool
    {
        /* @phpstan-ignore-next-line treatPhpDocTypesAsCertain checks soft check but this is the hard check */
        return \is_int($item);
    }
}
