<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Utility\Collection\Scalar;

use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<string>
 */
final class StringCollection extends AbstractCollection
{
    public function join(string $glue = ''): string
    {
        return \implode($glue, $this->items);
    }

    #[\Override]
    public function asUnique(): static
    {
        $result = $this->withoutItems();

        $result->push(\array_map('strval', \array_keys(\array_flip($this->items))));

        return $result;
    }

    #[\Override]
    protected function isValidItem(mixed $item): bool
    {
        return \is_string($item);
    }
}
