<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

abstract class PortalStorageContract
{
    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function set(
        PortalNodeKeyInterface $portalNodeKey,
        string $key,
        string $value,
        string $type,
        ?\DateInterval $ttl = null
    ): void;

    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function unset(PortalNodeKeyInterface $portalNodeKey, string $key): void;

    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function getValue(PortalNodeKeyInterface $portalNodeKey, string $key): string;

    /**
     * @throws NotFoundException
     * @throws UnsupportedStorageKeyException
     */
    abstract public function getType(PortalNodeKeyInterface $portalNodeKey, string $key): string;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function list(PortalNodeKeyInterface $portalNodeKey): iterable;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function has(PortalNodeKeyInterface $portalNodeKey, string $key): bool;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function clear(PortalNodeKeyInterface $portalNodeKey): void;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function getMultiple(PortalNodeKeyInterface $portalNodeKey, array $keys): array;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function deleteMultiple(PortalNodeKeyInterface $portalNodeKey, array $keys): void;
}
