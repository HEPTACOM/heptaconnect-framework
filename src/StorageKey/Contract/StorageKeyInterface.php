<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract;

interface StorageKeyInterface extends \JsonSerializable
{
    public function equals(StorageKeyInterface $other): bool;
}
