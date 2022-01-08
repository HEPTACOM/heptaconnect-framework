<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Repository;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingExceptionKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

abstract class MappingExceptionRepositoryContract
{
    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function create(
        PortalNodeKeyInterface $portalNodeKey,
        MappingNodeKeyInterface $mappingNodeKey,
        \Throwable $throwable
    ): MappingExceptionKeyInterface;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function listByMapping(MappingKeyInterface $mappingKey): iterable;

    /**
     * @psalm-param class-string<\Throwable> $type
     *
     * @throws UnsupportedStorageKeyException
     */
    abstract public function listByMappingAndType(MappingKeyInterface $mappingKey, string $type): iterable;

    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function delete(MappingExceptionKeyInterface $key): void;
}
