<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Portal;

use Composer\Autoload\ClassLoader;
use Heptacom\HeptaConnect\Core\Configuration\Contract\ConfigurationServiceInterface;
use Heptacom\HeptaConnect\Core\Portal\File\Filesystem\Contract\FilesystemFactoryInterface;
use Heptacom\HeptaConnect\Core\Portal\FlowComponentRegistry;
use Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerBuilder;
use Heptacom\HeptaConnect\Core\Portal\PortalStorageFactory;
use Heptacom\HeptaConnect\Core\Storage\Contract\RequestStorageContract;
use Heptacom\HeptaConnect\Core\Storage\Filesystem\FilesystemFactory;
use Heptacom\HeptaConnect\Core\Support\HttpMiddlewareCollector;
use Heptacom\HeptaConnect\Core\Test\Fixture\HttpClientInterfaceDecorator;
use Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandlerUrlProviderFactoryInterface;
use Heptacom\HeptaConnect\Core\Web\Http\Contract\HttpHandleServiceInterface;
use Heptacom\HeptaConnect\Portal\Base\File\FileReferenceResolverContract;
use Heptacom\HeptaConnect\Portal\Base\File\Filesystem\Contract\FilesystemInterface;
use Heptacom\HeptaConnect\Portal\Base\Flow\DirectEmission\DirectEmissionFlowContract;
use Heptacom\HeptaConnect\Portal\Base\Parallelization\Contract\ResourceLockingContract;
use Heptacom\HeptaConnect\Portal\Base\Parallelization\Support\ResourceLockFacade;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\ConfigurationContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalStorageInterface;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection;
use Heptacom\HeptaConnect\Portal\Base\Profiling\ProfilerContract;
use Heptacom\HeptaConnect\Portal\Base\Profiling\ProfilerFactoryContract;
use Heptacom\HeptaConnect\Portal\Base\Publication\Contract\PublisherInterface;
use Heptacom\HeptaConnect\Portal\Base\Serialization\Contract\NormalizationRegistryContract;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepCloneContract;
use Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpClientContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\HttpClientMiddlewareInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\Psr7MessageCurlShellFormatterContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\Psr7MessageFormatterContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\Psr7MessageMultiPartFormDataBuilderInterface;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\Contract\Psr7MessageRawHttpFormatterContract;
use Heptacom\HeptaConnect\Portal\Base\Web\Http\HttpHandlerUrlProviderInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use HeptacomFixture\Portal\A\AutomaticService\ExceptionNotInContainer;
use HeptacomFixture\Portal\A\AutomaticService\InboundHttpMiddleware;
use HeptacomFixture\Portal\A\AutomaticService\OutboundHttpMiddleware;
use HeptacomFixture\Portal\A\Dto\ShouldNotBeAService;
use HeptacomFixture\Portal\A\ManualService\ExceptionInContainer;
use HeptacomFixture\Portal\A\Portal;
use HeptacomFixture\Portal\Extension\PortalExtension;
use League\Flysystem\FilesystemInterface as FlysystemFilesystemInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @covers \Heptacom\HeptaConnect\Core\File\FileReferenceFactory
 * @covers \Heptacom\HeptaConnect\Core\Portal\Exception\ServiceNotInstantiable
 * @covers \Heptacom\HeptaConnect\Core\Portal\Exception\ServiceNotInstantiableEndlessLoopDetected
 * @covers \Heptacom\HeptaConnect\Core\Portal\PortalConfiguration
 * @covers \Heptacom\HeptaConnect\Core\Portal\PortalLogger
 * @covers \Heptacom\HeptaConnect\Core\Portal\PortalStackServiceContainerBuilder
 * @covers \Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\AddHttpMiddlewareClientCompilerPass
 * @covers \Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\AddHttpMiddlewareCollectorCompilerPass
 * @covers \Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\AddConfigurationBindingsCompilerPass
 * @covers \Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\SetConfigurationAsParameterCompilerPass
 * @covers \Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\AllDefinitionDefaultsCompilerPass
 * @covers \Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\BuildDefinitionForFlowComponentRegistryCompilerPass
 * @covers \Heptacom\HeptaConnect\Core\Portal\ServiceContainerCompilerPass\RemoveAutoPrototypedDefinitionsCompilerPass
 * @covers \Heptacom\HeptaConnect\Core\Support\HttpMiddlewareCollector
 * @covers \Heptacom\HeptaConnect\Core\Web\Http\HttpMiddlewareClient
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Parallelization\Support\ResourceLockFacade
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection
 */
final class PortalStackServiceContainerBuilderTest extends TestCase
{
    private ClassLoader $classLoader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classLoader = new ClassLoader();
        $this->classLoader->addPsr4('HeptacomFixture\\Portal\\A\\', __DIR__ . '/../../../test-composer-integration/portal-package/src/');
        $this->classLoader->addPsr4('HeptacomFixture\\Portal\\AdditionalPackage\\', __DIR__ . '/../../../test-composer-integration/package-package/src/');
        $this->classLoader->addPsr4('HeptacomFixture\\Portal\\Extension\\', __DIR__ . '/../../../test-composer-integration/portal-package-extension/src/');
        $this->classLoader->register();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->classLoader->unregister();
    }

    public function testServiceRetrieval(): void
    {
        $container = $this->getContainerBuilder();
        $container->compile();

        static::assertTrue($container->has(ClientInterface::class));
        static::assertTrue($container->has(ConfigurationContract::class));
        static::assertTrue($container->has(DeepCloneContract::class));
        static::assertTrue($container->has(DeepObjectIteratorContract::class));
        static::assertTrue($container->has(DirectEmissionFlowContract::class));
        static::assertTrue($container->has(FilesystemInterface::class));
        static::assertTrue($container->has(FlysystemFilesystemInterface::class));
        static::assertTrue($container->has(LoggerInterface::class));
        static::assertTrue($container->has(NormalizationRegistryContract::class));
        static::assertTrue($container->has(PortalContract::class));
        static::assertTrue($container->has(PortalExtensionCollection::class));
        static::assertTrue($container->has(PortalNodeKeyInterface::class));
        static::assertTrue($container->has(PortalStorageInterface::class));
        static::assertTrue($container->has(ProfilerContract::class));
        static::assertTrue($container->has(PublisherInterface::class));
        static::assertTrue($container->has(RequestFactoryInterface::class));
        static::assertTrue($container->has(ResourceLockFacade::class));
        static::assertTrue($container->has(UriFactoryInterface::class));
        static::assertTrue($container->has(HttpClientContract::class));

        static::assertTrue($container->has(HttpHandlerUrlProviderInterface::class));

        static::assertTrue($container->has(FlowComponentRegistry::class));

        static::assertTrue($container->has(ExceptionInContainer::class));
        static::assertFalse($container->has(ExceptionNotInContainer::class));

        static::assertTrue($container->has('manual-service-by-portal'));
        static::assertTrue($container->has('manual-service-by-additional-package'));

        static::assertTrue($container->has(InboundHttpMiddleware::class));
        static::assertTrue($container->has(OutboundHttpMiddleware::class));
        static::assertTrue($container->has(HttpMiddlewareCollector::class));

        static::assertTrue($container->has(Psr7MessageFormatterContract::class));
        static::assertTrue($container->has(Psr7MessageCurlShellFormatterContract::class));
        static::assertTrue($container->has(Psr7MessageRawHttpFormatterContract::class));
        static::assertTrue($container->has(Psr7MessageMultiPartFormDataBuilderInterface::class));

        static::assertInstanceOf(Psr7MessageRawHttpFormatterContract::class, $container->get(Psr7MessageFormatterContract::class));
        /** @var HttpMiddlewareCollector $middlewareCollector */
        $middlewareCollector = $container->get(HttpMiddlewareCollector::class);
        static::assertCount(1, $middlewareCollector);
    }

    public function testServiceDecoration(): void
    {
        $container = $this->getContainerBuilder();
        $container->setDefinition(
            HttpClientInterfaceDecorator::class,
            (new Definition())
                ->setDecoratedService(ClientInterface::class)
                ->setArguments([new Reference(HttpClientInterfaceDecorator::class . '.inner')])
        );
        $container->compile();

        static::assertTrue($container->has(ClientInterface::class));
        static::assertTrue($container->has(HttpClientInterfaceDecorator::class));
        static::assertFalse($container->has(ShouldNotBeAService::class));
    }

    public function testHttpMiddlewareTaggedServices(): void
    {
        $container = $this->getContainerBuilder();

        $mockRequest = $this->createMock(RequestInterface::class);
        $mockResponse = $this->createMock(ResponseInterface::class);

        $httpMiddlewareA = $this->createMock(HttpClientMiddlewareInterface::class);
        $httpMiddlewareA->expects(static::once())->method('process')->willReturnCallback(
            fn (RequestInterface $request, ClientInterface $handler) => $handler->sendRequest($request)
        );

        $httpMiddlewareB = $this->createMock(HttpClientMiddlewareInterface::class);
        $httpMiddlewareB->expects(static::once())->method('process')->willReturn($mockResponse);

        $this->setSyntheticServices($container, [
            'mock.http_middleware.a' => $httpMiddlewareA,
            'mock.http_middleware.b' => $httpMiddlewareB,
        ]);

        $container->compile();

        /** @var ClientInterface $httpClient */
        $httpClient = $container->get(ClientInterface::class);

        $actualResponse = $httpClient->sendRequest($mockRequest);

        static::assertSame($mockResponse, $actualResponse);
    }

    public function testHttpMiddlewareTaggedServicesOrder(): void
    {
        $container = $this->getContainerBuilder();

        $mockRequest = $this->createMock(RequestInterface::class);
        $mockResponse = $this->createMock(ResponseInterface::class);

        $httpMiddlewareA = $this->createMock(HttpClientMiddlewareInterface::class);
        $httpMiddlewareA->expects(static::never())->method('process');

        $httpMiddlewareB = $this->createMock(HttpClientMiddlewareInterface::class);
        $httpMiddlewareB->expects(static::once())->method('process')->willReturn($mockResponse);

        $this->setSyntheticServices($container, [
            'mock.http_middleware.b' => $httpMiddlewareB,
            'mock.http_middleware.a' => $httpMiddlewareA,
        ]);

        $container->compile();

        /** @var ClientInterface $httpClient */
        $httpClient = $container->get(ClientInterface::class);

        $actualResponse = $httpClient->sendRequest($mockRequest);

        static::assertSame($mockResponse, $actualResponse);
    }

    private function getContainerBuilder(): ContainerBuilder
    {
        $portal = new Portal();
        $portalExtension = new PortalExtension();

        $configurationService = $this->createMock(ConfigurationServiceInterface::class);
        $configurationService->expects(static::atLeastOnce())
            ->method('getPortalNodeConfiguration')
            ->willReturn($portalExtension->extendConfiguration($portal->getConfigurationTemplate())->resolve());

        $httpHandlerUrlProvider = $this->createMock(HttpHandlerUrlProviderInterface::class);
        $httpHandlerUrlProviderFactory = $this->createMock(HttpHandlerUrlProviderFactoryInterface::class);
        $httpHandlerUrlProviderFactory->method('factory')->willReturn($httpHandlerUrlProvider);

        $builder = new PortalStackServiceContainerBuilder(
            $this->createMock(LoggerInterface::class),
            $this->createMock(NormalizationRegistryContract::class),
            $this->createMock(PortalStorageFactory::class),
            $this->createMock(ResourceLockingContract::class),
            $this->createMock(ProfilerFactoryContract::class),
            $this->createMock(StorageKeyGeneratorContract::class),
            $this->createMock(FilesystemFactory::class),
            $configurationService,
            $this->createMock(PublisherInterface::class),
            $httpHandlerUrlProviderFactory,
            $this->createMock(RequestStorageContract::class),
            $this->createMock(FilesystemFactoryInterface::class),
            $this->createMock(Psr7MessageCurlShellFormatterContract::class),
            $this->createMock(Psr7MessageRawHttpFormatterContract::class),
            $this->createMock(Psr7MessageMultiPartFormDataBuilderInterface::class),
        );
        $builder->setDirectEmissionFlow($this->createMock(DirectEmissionFlowContract::class));
        $builder->setFileReferenceResolver($this->createMock(FileReferenceResolverContract::class));
        $builder->setHttpHandleService($this->createMock(HttpHandleServiceInterface::class));
        $container = $builder->build(
            $portal,
            new PortalExtensionCollection([
                $portalExtension,
            ]),
            $this->createMock(PortalNodeKeyInterface::class),
        );

        return $container;
    }

    /**
     * @param object[] $services
     */
    private function setSyntheticServices(ContainerBuilder $containerBuilder, array $services): void
    {
        foreach ($services as $id => $service) {
            $definitionId = (string) $id;
            $containerBuilder->set($definitionId, $service);
            $definition = (new Definition())
                ->setSynthetic(true)
                ->setClass($service::class);
            $containerBuilder->setDefinition($definitionId, $definition);
        }
    }
}
