<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Support;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\ReadException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\StorageKeyDataNotSupportedException;
use Heptacom\HeptaConnect\Ui\Admin\Base\Contract\Exception\StorageKeyNotSupportedException;

interface StorageKeyAccessorInterface
{
    /**
     * @throws ReadException
     * @throws StorageKeyDataNotSupportedException
     */
    public function deserialize(string $keyData): StorageKeyInterface;

    /**
     * @throws ReadException
     * @throws StorageKeyNotSupportedException
     */
    public function serialize(StorageKeyInterface $storageKey): string;

    /**
     * @throws ReadException
     * @throws StorageKeyNotSupportedException
     */
    public function exists(StorageKeyInterface $storageKey): bool;
}
