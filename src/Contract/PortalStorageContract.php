<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

abstract class PortalStorageContract
{
    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function set(PortalNodeKeyInterface $portalNodeKey, string $key, string $value, string $type): void;

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
    abstract public function has(PortalNodeKeyInterface $portalNodeKey, string $key): bool;
}
