<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\StorageMappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\StoragePortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\MappingCollection;
use Heptacom\HeptaConnect\Storage\Base\Exception\StorageMethodNotImplemented;

interface StorageInterface
{
    /**
     * @throws StorageMethodNotImplemented
     */
    public function getConfiguration(StoragePortalNodeKeyInterface $portalNodeId): array;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function setConfiguration(StoragePortalNodeKeyInterface $portalNodeId, array $data): void;

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
    public function createMappingNodes(array $datasetEntityClassNames, StoragePortalNodeKeyInterface $portalNodeKey): array;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function getMapping(
        StorageMappingNodeKeyInterface $mappingNodeKey,
        StoragePortalNodeKeyInterface $portalNodeKey
    ): ?MappingInterface;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function createMappings(MappingCollection $mappings): void;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $entityClassName
     *
     * @throws StorageMethodNotImplemented
     *
     * @psalm-return array<array-key, StoragePortalNodeKeyInterface>
     */
    public function getRouteTargets(StoragePortalNodeKeyInterface $sourcePortalNodeKey, string $entityClassName): array;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $entityClassName
     *
     * @throws StorageMethodNotImplemented
     */
    public function createRouteTarget(
        StoragePortalNodeKeyInterface $sourcePortalNodeKey,
        StoragePortalNodeKeyInterface $targetPortalNodeKey,
        string $entityClassName
    ): void;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Portal\Base\Contract\StorageKeyInterface> $keyClassName
     *
     * @throws StorageMethodNotImplemented
     */
    public function generateKey(string $keyClassName): StorageKeyInterface;
}
