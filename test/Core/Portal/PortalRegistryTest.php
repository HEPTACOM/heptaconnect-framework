<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Portal;

use Composer\Autoload\ClassLoader;
use Heptacom\HeptaConnect\Core\Portal\ComposerPortalLoader;
use Heptacom\HeptaConnect\Core\Portal\PortalFactory;
use Heptacom\HeptaConnect\Core\Portal\PortalRegistry;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalExtension\Find\PortalExtensionFindResult;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalExtension\PortalExtensionFindActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNode\PortalNodeGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use HeptacomFixture\Portal\A\Portal;
use HeptacomFixture\Portal\Extension\PortalExtension;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Portal\PortalRegistry
 */
final class PortalRegistryTest extends TestCase
{
    private ClassLoader $classLoader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classLoader = new ClassLoader();
        $this->classLoader->addPsr4('HeptacomFixture\\Portal\\A\\', __DIR__ . '/../../../test-composer-integration/portal-package/src/');
        $this->classLoader->addPsr4('HeptacomFixture\\Portal\\Extension\\', __DIR__ . '/../../../test-composer-integration/portal-package-extension/src/');
        $this->classLoader->register();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->classLoader->unregister();
    }

    public function testExtensionAvailability(): void
    {
        $portalFactory = $this->createMock(PortalFactory::class);
        $portalLoader = $this->createMock(ComposerPortalLoader::class);
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);

        $portalExtensionFindResult = new PortalExtensionFindResult();
        $portalExtensionFindResult->add(PortalExtension::class, true);

        $portalNodeGetAction->method('get')->willReturn([new PortalNodeGetResult($portalNodeKey, Portal::class)]);
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
        $portalFactory = $this->createMock(PortalFactory::class);
        $portalLoader = $this->createMock(ComposerPortalLoader::class);
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);
        $portalNodeGetAction = $this->createMock(PortalNodeGetActionInterface::class);
        $portalExtensionFindAction = $this->createMock(PortalExtensionFindActionInterface::class);
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);

        $portalExtensionFindResult = new PortalExtensionFindResult();
        $portalExtensionFindResult->add(PortalExtension::class, false);

        $portalNodeGetAction->method('get')->willReturn([new PortalNodeGetResult($portalNodeKey, Portal::class)]);
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
