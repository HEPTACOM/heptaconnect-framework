<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\DelegatingLoaderLoadException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This is the contract for all packages in a portal container, and it must be extended by:
 *   - any portal @see PortalContract
 *   - any portal extension @see PortalExtensionContract
 *   - any additional packages returned by @see PackageContract::getAdditionalPackages()
 *
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
abstract class PackageContract
{
    public final function __construct()
    {
    }

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
     * This method is executed during the building of a portal container.
     * It can be extended to influence the build steps on a fine grain level.
     * If applicable, use @see ContainerBuilder::addCompilerPass() to apply changes after all build steps are completed.
     *
     * @throws DelegatingLoaderLoadException
     */
    public function buildContainer(ContainerBuilder $containerBuilder): void
    {
        $this->registerContainerFile($containerBuilder);
    }

    /**
     * Returns instances of packages that will be loaded additionally after this package.
     * Those packages can contain extra features that are shared across portals or portal extensions.
     * Packages are deduplicated in the build process, so only the first instance of each package class is used.
     * The instances returned here SHOULD NOT contain any stateful information.
     *
     * @return iterable<PackageContract>
     */
    public function getAdditionalPackages(): iterable
    {
        return [];
    }

    /**
     * Scans the path returned in @see getContainerConfigurationPath for files that match `services.{yml,yaml,xml,php}`
     * The found files are loaded as service definitions for the @see ContainerBuilder
     *
     * @throws DelegatingLoaderLoadException
     */
    final protected function registerContainerFile(ContainerBuilder $containerBuilder): void
    {
        $containerConfigurationPath = $this->getContainerConfigurationPath();

        $fileLocator = new FileLocator($containerConfigurationPath);
        $loaderResolver = new LoaderResolver([
            new XmlFileLoader($containerBuilder, $fileLocator),
            new YamlFileLoader($containerBuilder, $fileLocator),
            new PhpFileLoader($containerBuilder, $fileLocator),
        ]);
        $delegatingLoader = new DelegatingLoader($loaderResolver);
        $directory = $containerConfigurationPath . \DIRECTORY_SEPARATOR . 'services.';
        $files = [
            $directory . 'yml',
            $directory . 'yaml',
            $directory . 'xml',
            $directory . 'php',
        ];

        foreach ($files as $serviceDefinitionPath) {
            if (!\is_file($serviceDefinitionPath)) {
                continue;
            }

            try {
                $delegatingLoader->load($serviceDefinitionPath);
            } catch (\Throwable $throwable) {
                throw new DelegatingLoaderLoadException(
                    $serviceDefinitionPath,
                    1674923696,
                    $throwable
                );
            }
        }
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
     * @return array<string, string>|null
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

        $composerJson = \json_decode($composerJsonContent, true, 512, \JSON_THROW_ON_ERROR);

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

        /** @var array<string, string>|null $psr4 */
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
