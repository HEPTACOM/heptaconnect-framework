<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Support;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\StorageKeyDataNotSupportedException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\StorageKeyNotSupportedException;

interface StorageKeyAccessorInterface
{
    /**
     * @throws StorageKeyDataNotSupportedException
     */
    public function deserialize(string $keyData): StorageKeyInterface;

    /**
     * @throws StorageKeyNotSupportedException
     */
    public function serialize(StorageKeyInterface $storageKey): string;

    /**
     * @throws StorageKeyNotSupportedException
     */
    public function exists(StorageKeyInterface $storageKey): bool;
}
