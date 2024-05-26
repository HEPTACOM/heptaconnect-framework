<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\FileReferenceRequestKeyInterface;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;

/**
 * @extends AbstractCollection<FileReferenceRequestKeyInterface>
 */
final class FileReferenceRequestKeyCollection extends AbstractCollection
{
    #[\Override]
    public function contains($value): bool
    {
        return $this->containsByEqualsCheck(
            $value,
            static fn (StorageKeyInterface $a, StorageKeyInterface $b): bool => $a->equals($b)
        );
    }

    #[\Override]
    protected function isValidItem(mixed $item): bool
    {
        return $item instanceof FileReferenceRequestKeyInterface;
    }
}
