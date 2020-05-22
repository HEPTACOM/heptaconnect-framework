<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\StorageKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\StoragePortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\MappingCollection;
use Heptacom\HeptaConnect\Storage\Base\Exception\StorageMethodNotImplemented;

interface StorageInterface
{
    /**
     * @throws StorageMethodNotImplemented
     */
    public function getConfiguration(string $portalNodeId): array;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function setConfiguration(string $portalNodeId, array $data): void;

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
    public function createMappingNodes(array $datasetEntityClassNames, StoragePortalNodeKeyInterface $portalNodeId): array;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function getMapping(string $mappingNodeId, string $portalNodeId): ?MappingInterface;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function createMappings(MappingCollection $mappings): void;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $entityClassName
     *
     * @throws StorageMethodNotImplemented
     *
     * @psalm-return array<array-key, string>
     */
    public function getRouteTargets(string $sourcePortalNodeId, string $entityClassName): array;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface> $entityClassName
     *
     * @throws StorageMethodNotImplemented
     */
    public function addRouteTarget(string $sourcePortalNodeId, string $targetPortalNodeId, string $entityClassName): void;

    /**
     * @psalm-template T of \Heptacom\HeptaConnect\Portal\Base\Contract\StorageKeyInterface
     * @psalm-param class-string<T> $keyClassName
     * @psalm-return StorageKeyInterface&T
     *
     * @throws StorageMethodNotImplemented
     */
    public function generateKey(string $keyClassName): StorageKeyInterface;
}
