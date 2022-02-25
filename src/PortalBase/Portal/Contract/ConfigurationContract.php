<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

/**
 * Facade to access a portal node configuration by flattened paths.
 */
abstract class ConfigurationContract
{
    /**
     * Return configuration value by path.
     * Configuration nesting is resolved by dots.
     *
     * @return mixed
     */
    abstract public function get(string $name);

    /**
     * Returns true, when there is a configuration value by path.
     * Configuration nesting is resolved by dots.
     */
    abstract public function has(string $name): bool;

    /**
     * Returns every configuration key that can be queried.
     *
     * @return string[]
     */
    abstract public function keys(): array;
}
