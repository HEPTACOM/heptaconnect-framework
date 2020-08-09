<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

interface PortalStorageInterface
{
    public function get(string $key);

    public function set(string $key, $value): void;

    public function has(string $key): bool;

    public function unset(string $key): void;

    public function canGet(string $type): bool;

    public function canSet(string $type): bool;
}
