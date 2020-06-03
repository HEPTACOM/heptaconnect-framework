<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Support;

use Heptacom\HeptaConnect\Portal\Base\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\MappingCollection;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\StorageMethodNotImplemented;

abstract class StorageFallback implements StorageInterface
{
    public function getConfiguration(PortalNodeKeyInterface $portalNodeKey): array
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function setConfiguration(PortalNodeKeyInterface $portalNodeKey, array $data): void
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function createMappingNodes(array $datasetEntityClassNames, PortalNodeKeyInterface $portalNodeKey): array
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function getMapping(
        MappingNodeKeyInterface $mappingNodeKey,
        PortalNodeKeyInterface $portalNodeKey
    ): ?MappingInterface {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function createMappings(MappingCollection $mappings): void
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function addMappingException(MappingInterface $mapping, \Throwable $throwable): void
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function removeMappingException(MappingInterface $mapping, string $type): void
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function getRouteTargets(PortalNodeKeyInterface $sourcePortalNodeKey, string $entityClassName): array
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function createRouteTarget(
        PortalNodeKeyInterface $sourcePortalNodeKey,
        PortalNodeKeyInterface $targetPortalNodeKey,
        string $entityClassName
    ): void {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function generateKey(string $keyClassName): StorageKeyInterface
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }
}
