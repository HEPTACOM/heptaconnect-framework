<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Repository;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\MappingKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;
use Heptacom\HeptaConnect\Storage\Base\MappingCollection;

abstract class MappingRepositoryContract
{
    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function read(MappingKeyInterface $key): MappingInterface;

    /**
     * @psalm-return iterable<\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingKeyInterface>
     *
     * @throws UnsupportedStorageKeyException
     */
    abstract public function listByMappingNode(MappingNodeKeyInterface $mappingNodeKey): iterable;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityType
     * @psalm-return iterable<\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingKeyInterface>
     *
     * @throws UnsupportedStorageKeyException
     */
    abstract public function listByPortalNodeAndType(
        PortalNodeKeyInterface $portalNodeKey,
        string $entityType
    ): iterable;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function create(
        PortalNodeKeyInterface $portalNodeKey,
        MappingNodeKeyInterface $mappingNodeKey,
        ?string $externalId
    ): MappingKeyInterface;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function createList(MappingCollection $mappings): MappingKeyCollection;

    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function delete(MappingKeyInterface $key): void;
}
