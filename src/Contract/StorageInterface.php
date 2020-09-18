<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\StorageMethodNotImplemented;
use Heptacom\HeptaConnect\Storage\Base\MappingNodeStructCollection;

interface StorageInterface
{
    public function getMappingNode(string $datasetEntityClassName, PortalNodeKeyInterface $portalNodeKey, string $externalId): ?MappingNodeStructInterface;

    /**
     * @param array<
     *     array-key,
     *     class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>
     * > $datasetEntityClassNames
     *
     * @throws StorageMethodNotImplemented
     */
    public function createMappingNodes(array $datasetEntityClassNames, PortalNodeKeyInterface $portalNodeKey): MappingNodeStructCollection;

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
    public function updateMappings(MappingCollection $mappings): void;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function deleteMappings(MappingCollection $mappings): void;

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
}
