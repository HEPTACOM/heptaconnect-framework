<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Support;

use Heptacom\HeptaConnect\Storage\Base\Contract\StorageInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\StorageMethodNotImplemented;

abstract class StorageFallback implements StorageInterface
{
    public function getConfiguration(string $portalNodeId): array
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function setConfiguration(string $portalNodeId, array $data): array
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }
}
