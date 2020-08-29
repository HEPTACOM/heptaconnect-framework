<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

abstract class StorageKeyGeneratorContract
{
    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface> $keyClassName
     *
     * @throws UnsupportedStorageKeyException
     */
    abstract public function generateKey(string $keyClassName): StorageKeyInterface;
}
