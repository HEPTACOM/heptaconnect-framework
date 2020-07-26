<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\WebhookInterface;
use Heptacom\HeptaConnect\Portal\Base\MappingCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeStorageKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\Storage\Base\Exception\StorageMethodNotImplemented;

interface StorageInterface
{
    /**
     * @throws StorageMethodNotImplemented
     */
    public function getConfiguration(PortalNodeKeyInterface $portalNodeKey): array;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function setConfiguration(PortalNodeKeyInterface $portalNodeKey, array $data): void;

    public function getMappingNode(string $datasetEntityClassName, PortalNodeKeyInterface $portalNodeKey, string $externalId): ?MappingNodeStructInterface;

    /**
     * @param array<
     *     array-key,
     *     class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     * > $datasetEntityClassNames
     *
     * @throws StorageMethodNotImplemented
     *
     * @psalm-return array<array-key, MappingNodeStructInterface>
     */
    public function createMappingNodes(array $datasetEntityClassNames, PortalNodeKeyInterface $portalNodeKey): array;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function getMapping(
        MappingNodeKeyInterface $mappingNodeKey,
        PortalNodeKeyInterface $portalNodeKey
    ): ?MappingInterface;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function createMappings(MappingCollection $mappings): void;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function addMappingException(MappingInterface $mapping, \Throwable $throwable): void;

    /**
     * @psalm-param class-string<\Throwable> $type
     *
     * @throws StorageMethodNotImplemented
     */
    public function removeMappingException(MappingInterface $mapping, string $type): void;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $entityClassName
     *
     * @throws StorageMethodNotImplemented
     *
     * @psalm-return array<array-key, \Heptacom\HeptaConnect\Portal\Base\Contract\PortalNodeKeyInterface>
     */
    public function getRouteTargets(PortalNodeKeyInterface $sourcePortalNodeKey, string $entityClassName): array;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $entityClassName
     *
     * @throws StorageMethodNotImplemented
     */
    public function createRouteTarget(
        PortalNodeKeyInterface $sourcePortalNodeKey,
        PortalNodeKeyInterface $targetPortalNodeKey,
        string $entityClassName
    ): void;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Portal\Base\Contract\StorageKeyInterface> $keyClassName
     *
     * @throws StorageMethodNotImplemented
     */
    public function generateKey(string $keyClassName): StorageKeyInterface;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function createWebhook(string $url, string $handler): WebhookInterface;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function getWebhook(string $url): ?WebhookInterface;

    /**
     * @throws NotFoundException
     * @throws StorageMethodNotImplemented
     */
    public function getPortalNode(PortalNodeKeyInterface $portalNodeKey): string;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function listPortalNodes(?string $className = null): PortalNodeStorageKeyCollection;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function addPortalNode(string $className): PortalNodeKeyInterface;

    /**
     * @throws NotFoundException
     * @throws StorageMethodNotImplemented
     */
    public function removePortalNode(PortalNodeKeyInterface $portalNodeKey): void;
}
