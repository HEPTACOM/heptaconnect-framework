<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Contract;

abstract class ResourceLockStorageContract
{
    abstract public function create(string $key): void;

    abstract public function has(string $key): bool;

    abstract public function delete(string $key): void;
}
