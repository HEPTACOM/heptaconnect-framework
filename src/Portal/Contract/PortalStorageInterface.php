<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

interface PortalStorageInterface
{
    /**
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * @param mixed $value
     */
    public function set(string $key, $value, ?\DateInterval $ttl = null): void;

    public function list(): iterable;

    public function has(string $key): bool;

    public function unset(string $key): void;

    public function canGet(string $type): bool;

    public function canSet(string $type): bool;
}
