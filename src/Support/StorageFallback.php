<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Support;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Portal\Base\Webhook\Contract\WebhookInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\MappingNodeStructInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\StorageMethodNotImplemented;
use Heptacom\HeptaConnect\Storage\Base\MappingNodeStructCollection;

abstract class StorageFallback implements StorageInterface
{
    public function getMappingNode(string $datasetEntityClassName, PortalNodeKeyInterface $portalNodeKey, string $externalId): ?MappingNodeStructInterface
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function createMappingNodes(array $datasetEntityClassNames, PortalNodeKeyInterface $portalNodeKey): MappingNodeStructCollection
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

    public function updateMappings(MappingCollection $mappings): void
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function deleteMappings(MappingCollection $mappings): void
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

    public function getRouteTargets(PortalNodeKeyInterface $sourcePortalNodeKey, string $entityClassName): PortalNodeKeyCollection
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
}
