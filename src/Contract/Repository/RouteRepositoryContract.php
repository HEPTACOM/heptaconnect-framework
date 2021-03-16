<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract\Repository;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\RouteInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\RouteKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

abstract class RouteRepositoryContract
{
    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityClassName
     *
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function read(RouteKeyInterface $key): RouteInterface;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityClassName
     * @psalm-return iterable<\Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\RouteKeyInterface>
     *
     * @throws UnsupportedStorageKeyException
     */
    abstract public function listBySourceAndEntityType(
        PortalNodeKeyInterface $sourceKey,
        string $entityClassName
    ): iterable;

    /**
     * @psalm-param class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract> $entityClassName
     *
     * @throws UnsupportedStorageKeyException
     */
    abstract public function create(
        PortalNodeKeyInterface $sourceKey,
        PortalNodeKeyInterface $targetKey,
        string $entityClassName
    ): RouteKeyInterface;
}
