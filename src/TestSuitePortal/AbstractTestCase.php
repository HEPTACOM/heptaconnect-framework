<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Portal;

use Composer\Autoload\ClassLoader;
use Heptacom\HeptaConnect\Core\Component\Composer\PackageConfigurationLoader;
use Heptacom\HeptaConnect\Core\Configuration\ConfigurationService;
use Heptacom\HeptaConnect\Core\Configuration\Contract\ConfigurationServiceInterface;
use Heptacom\HeptaConnect\Core\Configuration\Contract\PortalNodeConfigurationProcessorInterface;
use Heptacom\HeptaConnect\Core\Portal\ComposerPortalLoader;
use Heptacom\HeptaConnect\Core\Portal\Contract\PortalRegistryInterface;
use Heptacom\HeptaConnect\Core\Portal\Contract\PortalStackServiceContainerBuilderInterface;
use Heptacom\HeptaConnect\Core\Portal\PortalFactory;
use Heptacom\HeptaConnect\Core\Portal\PortalRegistry;
use Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerBuilder;
use Heptacom\HeptaConnect\Core\Portal\PortalStorageFactory;
use Heptacom\HeptaConnect\Core\Storage\Contract\RequestStorageContract;
use Heptacom\HeptaConnect\Core\Storage\Filesystem\FilesystemFactory;
use Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandlerUrlProviderFactoryInterface;
use Heptacom\HeptaConnect\Portal\Base\File\FileReferenceResolverContract;
use Heptacom\HeptaConnect\Portal\Base\Flow\DirectEmission\DirectEmissionFlowContract;
use Heptacom\HeptaConnect\Portal\Base\Parallelization\Contract\ResourceLockingContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection;
use Heptacom\HeptaConnect\Portal\Base\Profiling\ProfilerFactoryContract;
use Heptacom\HeptaConnect\Portal\Base\Publication\Contract\PublisherInterface;
use Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\NormalizationRegistryContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerUrlProviderInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration\PortalNodeConfigurationGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration\PortalNodeConfigurationSetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Heptacom\HeptaConnect\Storage\Base\PreviewPortalNodeKey;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Cache\Adapter\NullAdapter;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractTestCase extends TestCase
{
    protected function getPortal(): PortalContract
    {
        $composerPortalLoader = $this->getPortalLoader();
        $portal = $composerPortalLoader->getPortals()->first();

        if ($portal instanceof PortalContract) {
            return $portal;
        }

        throw new \LogicException(
            'Unable to locate any portal. Did you forget to define it in the extra-section of your composer.json?'
        );
    }

    protected function getPortalRegistry(): PortalRegistryInterface
    {
        $portalRegistry = new PortalRegistry(
            new PortalFactory(),
            $this->getPortalLoader(),
            $this->createMock(StorageKeyGeneratorContract::class),
            $this->createMock(PortalNodeGetActionInterface::class),
            $this->createMock(PortalExtensionFindActionInterface::class)
        );

        return $portalRegistry;
    }

    protected function getPortalNodeKey(): PortalNodeKeyInterface
    {
        $composerPortalLoader = $this->getPortalLoader();
        $portal = $composerPortalLoader->getPortals()->first();

        return new PreviewPortalNodeKey(\get_class($portal));
    }

    protected function getPortalLoader(): ComposerPortalLoader
    {
        $packageRoot = \dirname((new \ReflectionClass(ClassLoader::class))->getFileName(), 3);
        $loader = new PackageConfigurationLoader($packageRoot . '/composer.json', new NullAdapter());

        return new ComposerPortalLoader($loader, new PortalFactory(), new NullLogger());
    }

    protected function getConfigurationService(): ConfigurationServiceInterface
    {
        $configurationProcessor = $this->createMock(PortalNodeConfigurationProcessorInterface::class);
        $configurationProcessor->method('read')->willReturn($this->getConfig());

        return new ConfigurationService(
            $this->getPortalRegistry(),
            $this->createMock(PortalNodeConfigurationGetActionInterface::class),
            $this->createMock(PortalNodeConfigurationSetActionInterface::class),
            [$configurationProcessor]
        );
    }

    protected function getConfig(): array
    {
        return [];
    }

    protected function getContainerBuilder(array $services = []): PortalStackServiceContainerBuilderInterface
    {
        $httpHandlerUrlProvider = $this->createMock(HttpHandlerUrlProviderInterface::class);
        $httpHandlerUrlProviderFactory = $this->createMock(HttpHandlerUrlProviderFactoryInterface::class);
        $httpHandlerUrlProviderFactory->method('factory')->willReturn($httpHandlerUrlProvider);

        $builder = new PortalStackServiceContainerBuilder(
            $services[LoggerInterface::class] ?? $this->getLogger(),
            $services[NormalizationRegistryContract::class] ?? $this->createMock(NormalizationRegistryContract::class),
            $services[PortalStorageFactory::class] ?? $this->createMock(PortalStorageFactory::class),
            $services[ResourceLockingContract::class] ?? $this->createMock(ResourceLockingContract::class),
            $services[ProfilerFactoryContract::class] ?? $this->getProfilerFactory(),
            $services[StorageKeyGeneratorContract::class] ?? $this->createMock(StorageKeyGeneratorContract::class),
            $services[FilesystemFactory::class] ?? $this->createMock(FilesystemFactory::class),
            $services[ConfigurationServiceInterface::class] ?? $this->getConfigurationService(),
            $services[PublisherInterface::class] ?? $this->createMock(PublisherInterface::class),
            $services[HttpHandlerUrlProviderFactoryInterface::class] ?? $httpHandlerUrlProviderFactory,
            $services[RequestStorageContract::class] ?? $this->createMock(RequestStorageContract::class),
        );

        $builder->setDirectEmissionFlow(
            $services[DirectEmissionFlowContract::class] ?? $this->createMock(DirectEmissionFlowContract::class)
        );

        $builder->setFileReferenceResolver(
            $services[FileReferenceResolverContract::class] ?? $this->createMock(FileReferenceResolverContract::class)
        );

        return $builder;
    }

    protected function getContainer(array $services = []): ContainerInterface
    {
        $container = $this->getContainerBuilder($services)->build(
            $this->getPortal(),
            new PortalExtensionCollection(),
            $this->getPortalNodeKey(),
        );

        $container->compile();

        return $container;
    }

    protected function getLogger(): LoggerInterface
    {
        return $this->createMock(LoggerInterface::class);
    }

    protected function getProfilerFactory(): ProfilerFactoryContract
    {
        return $this->createMock(ProfilerFactoryContract::class);
    }
}
