<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;

/**
 * This contract must only be extended by @see PortalContract and @see PortalExtensionContract
 * Its only purpose is to combine their features in a single class.
 *
 * @internal
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
abstract class PackageContract
{
    /**
     * Get a PSR4 definition to automatically build portal node dependency injection container.
     * Can be implemented as empty to disable automatic service creation.
     *
     * @return array<string, string>
     */
    public function getPsr4(): array
    {
        $path = $this->getPath();
        $composerPsr4 = $this->getComposerPsr4($path);

        if (\is_array($composerPsr4)) {
            return $composerPsr4;
        }

        $namespace = (new \ReflectionClass($this))->getNamespaceName() . '\\';
        $sourceDir = \rtrim($path, \DIRECTORY_SEPARATOR) . \DIRECTORY_SEPARATOR;

        return [
            $namespace => $sourceDir,
        ];
    }

    /**
     * Get a path to a directory containing package configuration files.
     */
    public function getContainerConfigurationPath(): string
    {
        return \implode(\DIRECTORY_SEPARATOR, [
            $this->getPath(),
            'Resources',
            'config',
        ]);
    }

    /**
     * Get a path to a directory containing package flow component scripts.
     */
    public function getFlowComponentsPath(): string
    {
        return \implode(\DIRECTORY_SEPARATOR, [
            $this->getPath(),
            'Resources',
            'flow-component',
        ]);
    }

    /**
     * Returns all FQCNs that must not to be present in a service's class hierarchy.
     * Useful to exclude interfaces and base classes used by DTOs that should not be part of the portal node container.
     * The result will only affect services auto-prototyped for this package.
     *
     * @return class-string[]
     */
    public function getContainerExcludedClasses(): array
    {
        return [
            \Throwable::class,
            DatasetEntityContract::class,
            CollectionInterface::class,
            AttachableInterface::class,
        ];
    }

    /**
     * Get the source code root directory of this package.
     */
    protected function getPath(): string
    {
        /** @var string $path */
        $path = (new \ReflectionClass($this))->getFileName();

        return \dirname($path);
    }

    /**
     * @return string[]|null
     */
    private function getComposerPsr4(string $path): ?array
    {
        $composerJsonPath = \dirname($path) . \DIRECTORY_SEPARATOR . 'composer.json';

        if (!\file_exists($composerJsonPath)) {
            return null;
        }

        $composerJsonContent = \file_get_contents($composerJsonPath);

        if ($composerJsonContent === false) {
            return null;
        }

        $composerJson = \json_decode($composerJsonContent, true, 512, JSON_THROW_ON_ERROR);

        if (!\is_array($composerJson)) {
            return null;
        }

        $composerExtra = $composerJson['extra'] ?? null;

        if (!\is_array($composerExtra)) {
            return null;
        }

        $composerExtraHeptaconnect = $composerExtra['heptaconnect'] ?? null;

        if (!\is_array($composerExtraHeptaconnect)) {
            return null;
        }

        /** @var array|null $portals */
        $portals = $composerExtraHeptaconnect['portals'] ?? null;

        if (!\is_array($portals)) {
            $portals = [];
        }

        /** @var array|null $portalExtensions */
        $portalExtensions = $composerExtraHeptaconnect['portalExtensions'] ?? null;

        if (!\is_array($portalExtensions)) {
            $portalExtensions = [];
        }

        /** @var array<int, string> $portals */
        $portals = \array_values(\array_filter($portals, 'is_string'));
        /** @var array<int, string> $portalExtensions */
        $portalExtensions = \array_values(\array_filter($portalExtensions, 'is_string'));

        if (!\in_array(static::class, [...$portals, ...$portalExtensions], true)) {
            return null;
        }

        $psr4 = $composerJson['autoload']['psr-4'] ?? null;

        if (!\is_array($psr4)) {
            return null;
        }

        return \array_map(
            fn (string $psr4Path) => \dirname($path) . \DIRECTORY_SEPARATOR . $psr4Path,
            $psr4
        );
    }
}
