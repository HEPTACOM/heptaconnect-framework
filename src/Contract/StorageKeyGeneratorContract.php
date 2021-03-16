<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

abstract class StorageKeyGeneratorContract
{
    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface> $keyClassName
     *
     * @throws UnsupportedStorageKeyException
     *
     * @deprecated Use generateKeys instead
     */
    abstract public function generateKey(string $keyClassName): StorageKeyInterface;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface> $keyClassName
     *
     * @throws UnsupportedStorageKeyException
     *
     * @return StorageKeyInterface[]
     * @psalm-return iterable<int, \Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface>
     */
    abstract public function generateKeys(string $keyClassName, int $count): iterable;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function serialize(StorageKeyInterface $key): string;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function deserialize(string $keyData): StorageKeyInterface;
}
