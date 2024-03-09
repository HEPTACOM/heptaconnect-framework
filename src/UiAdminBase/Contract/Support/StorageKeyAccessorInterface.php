<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Support;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\StorageKeyDataNotSupportedException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\StorageKeyNotSupportedException;

/**
 * Provides access to checks and transformations on storage keys.
 */
interface StorageKeyAccessorInterface
{
    /**
     * Transforms a string into a storage key, when the storage supports the given key data.
     * The return value can be passed into @see serialize do get the same string passed into here.
     *
     * @throws ReadException
     * @throws StorageKeyDataNotSupportedException
     */
    public function deserialize(string $keyData): StorageKeyInterface;

    /**
     * Transforms a storage key into a string, when the storage supports the given key.
     * The return value can be passed into @see deserialize do get a storage key pointing to the same data.
     *
     * @throws ReadException
     * @throws StorageKeyNotSupportedException
     */
    public function serialize(StorageKeyInterface $storageKey): string;

    /**
     * Performs a check, whether the data behind the storage key can be read from the storage.
     *
     * @throws ReadException
     * @throws StorageKeyNotSupportedException
     */
    public function exists(StorageKeyInterface $storageKey): bool;
}
