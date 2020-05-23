<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Contract;

interface StorageKeyInterface extends \JsonSerializable
{
    public function equals(StorageKeyInterface $other): bool;
}
