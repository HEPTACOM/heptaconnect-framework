<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Psr\SimpleCache\CacheInterface;

interface PortalStorageInterface extends CacheInterface
{
    public function list(): iterable;

    public function canGet(string $type): bool;

    public function canSet(string $type): bool;
}
