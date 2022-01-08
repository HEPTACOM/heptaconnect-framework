<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;

abstract class ConfigurationStorageContract
{
    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function getConfiguration(PortalNodeKeyInterface $portalNodeKey): array;

    /**
     * @throws UnsupportedStorageKeyException
     */
    abstract public function setConfiguration(PortalNodeKeyInterface $portalNodeKey, ?array $data): void;
}
