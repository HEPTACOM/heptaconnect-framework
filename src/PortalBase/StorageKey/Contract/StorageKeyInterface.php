<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract;

interface StorageKeyInterface extends \JsonSerializable
{
    /**
     * Compares this with a different instance of a storage key.
     * Returns true, when both storage keys refer to the same type and id.
     */
    public function equals(StorageKeyInterface $other): bool;
}
