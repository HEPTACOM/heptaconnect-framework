<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Bridge\PortalNode\Configuration;

/**
 * Helper to generate closures to generate configuration arrays.
 */
class PortalNodeConfigurationHelper
{
    /**
     * Create closure to read a mapped configuration read from environment variables.
     *
     * @return \Closure(): array
     */
    public function env(array $mappings): \Closure
    {
        return function () use ($mappings): array {
            return $this->resolveMapping($mappings, static fn (string $i) => \getenv($i));
        };
    }

    /**
     * Create closure to read a mapped configuration read from the given INI file.
     *
     * @return \Closure(): array
     */
    public function ini(string $iniFile, ?array $mappings = null): \Closure
    {
        return function () use ($mappings, $iniFile): array {
            $config = \parse_ini_file($iniFile, true, \INI_SCANNER_TYPED);

            if (!\is_array($config)) {
                throw new \RuntimeException('Can not load INI file', 1647801828);
            }

            if (!\is_array($mappings)) {
                return $config;
            }

            return $this->resolveMapping($mappings, fn (string $i) => $this->resolveDotPath($i, $config));
        };
    }

    /**
     * Create closure to read a mapped configuration read from the given JSON file.
     *
     * @return \Closure(): array
     */
    public function json(string $jsonFile, ?array $mappings = null): \Closure
    {
        return function () use ($mappings, $jsonFile): array {
            $config = \json_decode(\file_get_contents($jsonFile), true, 512, \JSON_THROW_ON_ERROR);

            if (!\is_array($config)) {
                throw new \RuntimeException('Can not load JSON file', 1647801829);
            }

            if (!\is_array($mappings)) {
                return $config;
            }

            return $this->resolveMapping($mappings, fn (string $i) => $this->resolveDotPath($i, $config));
        };
    }

    /**
     * Create closure to map the given array configuration.
     *
     * @return \Closure(): array
     */
    public function array(array $array, array $mappings): \Closure
    {
        return function () use ($mappings, $array): array {
            return $this->resolveMapping($mappings, fn (string $i) => $this->resolveDotPath($i, $array));
        };
    }

    /**
     * @param \Closure(string) $lookUp
     */
    private function resolveMapping(array $mappings, \Closure $lookUp): array
    {
        $result = [];

        foreach ($mappings as $key => $value) {
            if (\is_array($value)) {
                $result[$key] = $this->resolveMapping($value, $lookUp);

                continue;
            }

            $result[$key] = $lookUp((string) $value);
        }

        return $result;
    }

    /**
     * @psalm-return mixed
     */
    private function resolveDotPath(string $path, array $payload)
    {
        foreach (\explode('.', $path) as $step) {
            if (!isset($payload[$step])) {
                return null;
            }

            $payload = $payload[$step];
        }

        return $payload;
    }
}
