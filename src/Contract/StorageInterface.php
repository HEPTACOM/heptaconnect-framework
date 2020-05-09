<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Storage\Base\Exception\StorageMethodNotImplemented;

interface StorageInterface
{
    /**
     * @throws StorageMethodNotImplemented
     */
    public function configurationGet(string $portalNodeId): array;

    /**
     * @throws StorageMethodNotImplemented
     */
    public function configurationSet(string $portalNodeId, array $data): array;
}
