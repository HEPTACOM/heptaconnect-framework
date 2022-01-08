<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

abstract class ConfigurationContract
{
    abstract public function get(string $name);

    abstract public function has(string $name): bool;

    /**
     * @return string[]
     */
    abstract public function keys(): array;
}
