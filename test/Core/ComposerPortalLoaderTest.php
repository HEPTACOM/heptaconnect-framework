<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test;

use Heptacom\HeptaConnect\Core\Component\Composer\PackageConfiguration;
use Heptacom\HeptaConnect\Core\Component\Composer\PackageConfigurationClassMap;
use Heptacom\HeptaConnect\Core\Component\Composer\PackageConfigurationCollection;
use Heptacom\HeptaConnect\Core\Component\Composer\PackageConfigurationLoader;
use Heptacom\HeptaConnect\Core\Portal\ComposerPortalLoader;
use Heptacom\HeptaConnect\Core\Portal\Contract\PortalFactoryContract;
use Heptacom\HeptaConnect\Core\Portal\PortalFactory;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionType;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalType;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\StringCollection;
use HeptacomFixture\Portal\A\Portal;
use HeptacomFixture\Portal\Extension\PortalExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

#[CoversClass(PackageConfiguration::class)]
#[CoversClass(PackageConfigurationClassMap::class)]
#[CoversClass(PackageConfigurationCollection::class)]
#[CoversClass(PackageConfigurationLoader::class)]
#[CoversClass(ComposerPortalLoader::class)]
#[CoversClass(PortalFactoryContract::class)]
#[CoversClass(PortalFactory::class)]
#[CoversClass(PackageContract::class)]
#[CoversClass(PortalContract::class)]
#[CoversClass(PortalCollection::class)]
#[CoversClass(PortalExtensionCollection::class)]
#[CoversClass(PortalExtensionType::class)]
#[CoversClass(PortalType::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(StringCollection::class)]
final class ComposerPortalLoaderTest extends TestCase
{
    public function testInstantiateFromComposer(): void
    {
        require_once __DIR__ . '/../../test-composer-integration/portal-package/src/Portal.php';
        require_once __DIR__ . '/../../test-composer-integration/portal-package-extension/src/PortalExtension.php';

        $poolItem = $this->createMock(CacheItemInterface::class);
        $poolItem->method('isHit')->willReturn(false);
        $cachePool = $this->createMock(CacheItemPoolInterface::class);
        $cachePool->method('getItem')->willReturn($poolItem);

        $loader = new ComposerPortalLoader(
            new PackageConfigurationLoader(__DIR__ . '/../../test-composer-integration/composer.json', $cachePool),
            new PortalFactory(),
            $this->createMock(LoggerInterface::class)
        );
        $portals = [...$loader->getPortals()];
        $portalExtensions = $loader->getPortalExtensions();

        static::assertCount(1, $portals);
        static::assertCount(1, $portalExtensions);

        /** @var PortalContract $portal */
        foreach ($portals as $portal) {
            static::assertInstanceOf(Portal::class, $portal);
        }

        foreach ($portalExtensions as $portalExtension) {
            static::assertInstanceOf(PortalExtension::class, $portalExtension);
        }
    }
}
