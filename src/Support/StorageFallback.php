<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Support;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\StorageMethodNotImplemented;

abstract class StorageFallback implements StorageInterface
{
    public function addMappingException(MappingInterface $mapping, \Throwable $throwable): void
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function removeMappingException(MappingInterface $mapping, string $type): void
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }
}
