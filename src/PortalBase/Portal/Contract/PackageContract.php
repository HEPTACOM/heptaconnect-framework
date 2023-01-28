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
 */
abstract class PackageContract
{
    use PathMethodsTrait;

    /**
     * @final This method will become final in version 0.10
     */
    public function __construct()
    {
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
}
