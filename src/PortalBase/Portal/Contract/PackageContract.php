<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Portal\Contract;

use Heptacom\HeptaConnect\Core\Portal\Exception\DelegatingLoaderLoadException;
use Heptacom\HeptaConnect\Dataset\Base\Contract\AttachableInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
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

    public function buildContainer(ContainerBuilder $containerBuilder): void
    {
        $this->registerContainerFile($containerBuilder);
    }

    /**
     * @return iterable<PackageContract>
     */
    public function getAdditionalPackages(): iterable
    {
        return [];
    }

    /**
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
                throw new DelegatingLoaderLoadException($serviceDefinitionPath, $throwable);
            }
        }
    }
}
