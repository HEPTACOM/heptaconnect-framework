<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test;

use Heptacom\HeptaConnect\Core\Component\Composer\PackageConfiguration;
use Heptacom\HeptaConnect\Core\Component\Composer\PackageConfigurationLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\NullAdapter;

/**
 * @covers \Heptacom\HeptaConnect\Core\Component\Composer\PackageConfiguration
 * @covers \Heptacom\HeptaConnect\Core\Component\Composer\PackageConfigurationClassMap
 * @covers \Heptacom\HeptaConnect\Core\Component\Composer\PackageConfigurationCollection
 * @covers \Heptacom\HeptaConnect\Core\Component\Composer\PackageConfigurationLoader
 */
class ComposerPackageConfigurationLoaderTest extends TestCase
{
    public function testLoadingPlugin(): void
    {
        $loader = new PackageConfigurationLoader(__DIR__.'/../../test-composer-integration/composer.json', new NullAdapter());
        $configs = $loader->getPackageConfigurations();

        static::assertCount(4, $configs);
        static::assertCount(1, $configs->filter(
            fn (PackageConfiguration $pkg): bool => $pkg->getName() === 'heptacom-fixture/heptaconnect-portal-a'
        ));
        static::assertCount(1, $configs->filter(
            fn (PackageConfiguration $pkg): bool => $pkg->getName() === 'heptacom-fixture/heptaconnect-portal-extension-a'
        ));
        static::assertCount(3, $configs->filter(
            fn (PackageConfiguration $pkg): bool => $pkg->getTags()->filter(
                    fn (string $tag): bool => \str_contains($tag, 'portal')
                )->valid()
        ));
        static::assertCount(0, $configs->filter(
            fn (PackageConfiguration $pkg): bool => $pkg->getTags()->filter(
                    fn (string $tag): bool => !\str_starts_with($tag, 'heptaconnect-')
                )->valid()
        ));
        static::assertCount(2, $configs->filter(
            fn (PackageConfiguration $pkg): bool => \count(\array_filter(
                    \array_keys($pkg->getConfiguration()),
                    fn (string $configKey): bool => \str_contains($configKey, 'portal')
                )) > 0
        ));
        static::assertCount(4, $configs->filter(
            fn (PackageConfiguration $pkg): bool => $pkg->getAutoloadedFiles()->count() > 0
        ), 'When this fails it could be that the composer install in the test-integration is missing');
    }
}
