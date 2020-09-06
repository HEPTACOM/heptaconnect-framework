<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Support;

use Heptacom\HeptaConnect\Portal\Base\Cronjob\Contract\CronjobInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\CronjobKeyInterface;
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

    public function createWebhook(PortalNodeKeyInterface $portalNodeKey, string $url, string $handler, ?array $payload = null): WebhookInterface
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function getWebhook(string $url): ?WebhookInterface
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function getPortalNode(PortalNodeKeyInterface $portalNodeKey): string
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function listPortalNodes(?string $className = null): PortalNodeKeyCollection
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function addPortalNode(string $className): PortalNodeKeyInterface
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function removePortalNode(PortalNodeKeyInterface $portalNodeKey): void
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function createCronjob(PortalNodeKeyInterface $portalNodeKey, string $cronExpression, string $handler, \DateTimeInterface $nextExecution, ?array $payload = null): CronjobInterface
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function removeCronjob(CronjobKeyInterface $cronjobKey): void
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function setPortalStorageValue(PortalNodeKeyInterface $portalNodeKey, string $key, string $value, string $type): void
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function unsetPortalStorageValue(PortalNodeKeyInterface $portalNodeKey, string $key): void
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function getPortalStorageValue(PortalNodeKeyInterface $portalNodeKey, string $key): string
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function getPortalStorageType(PortalNodeKeyInterface $portalNodeKey, string $key): string
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }

    public function hasPortalStorageValue(PortalNodeKeyInterface $portalNodeKey, string $key): bool
    {
        throw new StorageMethodNotImplemented(static::class, __FUNCTION__);
    }
}
