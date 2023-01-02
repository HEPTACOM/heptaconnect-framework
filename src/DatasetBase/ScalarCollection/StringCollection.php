<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\ScalarCollection;

use Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection;

/**
 * @extends AbstractCollection<string>
 */
final class StringCollection extends AbstractCollection
{
    public function join(string $glue = ''): string
    {
        return \implode($glue, $this->items);
    }

    public function asUnique(): static
    {
        $result = $this->withoutItems();

        $result->push(\array_map('strval', \array_keys(\array_flip($this->items))));

        return $result;
    }

    protected function isValidItem(mixed $item): bool
    {
        /* @phpstan-ignore-next-line treatPhpDocTypesAsCertain checks soft check but this is the hard check */
        return \is_string($item);
    }
}
