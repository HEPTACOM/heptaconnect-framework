<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Portal;

use Composer\Autoload\ClassLoader;
use Heptacom\HeptaConnect\Core\Bridge\Portal\PortalLoaderInterface;
use Heptacom\HeptaConnect\Core\Portal\Contract\PortalFactoryContract;
use Heptacom\HeptaConnect\Core\Portal\PortalRegistry;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionType;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalType;
use Heptacom\HeptaConnect\Portal\Base\Portal\SupportedPortalType;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find\PortalExtensionFindResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use HeptacomFixture\Portal\A\Portal;
use HeptacomFixture\Portal\Extension\PortalExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(PortalRegistry::class)]
#[CoversClass(PackageContract::class)]
#[CoversClass(PortalContract::class)]
#[CoversClass(PortalExtensionContract::class)]
#[CoversClass(PortalExtensionCollection::class)]
#[CoversClass(PortalExtensionType::class)]
#[CoversClass(PortalType::class)]
#[CoversClass(SupportedPortalType::class)]
#[CoversClass(PortalNodeKeyCollection::class)]
#[CoversClass(PortalExtensionFindResult::class)]
#[CoversClass(PortalNodeGetCriteria::class)]
#[CoversClass(PortalNodeGetResult::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
final class PortalRegistryTest extends TestCase
{
    private ClassLoader $classLoader;

    #[\Override]
    protected function setUp(): void
    {
        $this->classLoader = new ClassLoader();
        $this->classLoader->addPsr4('HeptacomFixture\\Portal\\A\\', __DIR__ . '/../../../test-composer-integration/portal-package/src/');
        $this->classLoader->addPsr4('HeptacomFixture\\Portal\\Extension\\', __DIR__ . '/../../../test-composer-integration/portal-package-extension/src/');
        $this->classLoader->register();
    }

    #[\Override]
    protected function tearDown(): void
    {
        $this->classLoader->unregister();
    }

    public function testExtensionAvailability(): void
    {
        $portalFactory = $this->createMock(PortalFactoryContract::class);
        $portalLoader = $this->createMock(PortalLoaderInterface::class);
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);

        $portalExtensionFindResult = new PortalExtensionFindResult();
        $portalExtensionFindResult->add(PortalExtension::class(), true);

        $portalNodeGetAction->method('get')->willReturn([new PortalNodeGetResult($portalNodeKey, Portal::class())]);
        $storageKeyGenerator->method('serialize')->willReturn('foobar');
        $portalLoader->method('getPortalExtensions')->willReturn(new PortalExtensionCollection([new PortalExtension()]));
        $portalExtensionFindAction->method('find')->willReturn($portalExtensionFindResult);

        $portalRegistry = new PortalRegistry(
            $portalFactory,
            $portalLoader,
            $storageKeyGenerator,
            $portalNodeGetAction,
            $portalExtensionFindAction
        );

        $extensions = $portalRegistry->getPortalExtensions($portalNodeKey);
        static::assertSame(1, $extensions->count());
    }

    public function testExtensionUnavailability(): void
    {
        $portalFactory = $this->createMock(PortalFactoryContract::class);
        $portalLoader = $this->createMock(PortalLoaderInterface::class);
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);

        $portalExtensionFindResult = new PortalExtensionFindResult();
        $portalExtensionFindResult->add(PortalExtension::class(), false);

        $portalNodeGetAction->method('get')->willReturn([new PortalNodeGetResult($portalNodeKey, Portal::class())]);
        $storageKeyGenerator->method('serialize')->willReturn('foobar');
        $portalLoader->method('getPortalExtensions')->willReturn(new PortalExtensionCollection([new PortalExtension()]));
        $portalExtensionFindAction->method('find')->willReturn($portalExtensionFindResult);

        $portalRegistry = new PortalRegistry(
            $portalFactory,
            $portalLoader,
            $storageKeyGenerator,
            $portalNodeGetAction,
            $portalExtensionFindAction
        );

        $extensions = $portalRegistry->getPortalExtensions($portalNodeKey);
        static::assertSame(0, $extensions->count());
    }
}
