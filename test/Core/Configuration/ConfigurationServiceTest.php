<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Configuration;

use Heptacom\HeptaConnect\Core\Configuration\ConfigurationService;
use Heptacom\HeptaConnect\Core\Configuration\PortalNodeConfigurationProcessorService;
use Heptacom\HeptaConnect\Core\Portal\Contract\PortalRegistryInterface;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get\PortalNodeConfigurationGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNodeConfiguration\Get\PortalNodeConfigurationGetResult;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration\PortalNodeConfigurationGetActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\PortalNodeConfiguration\PortalNodeConfigurationSetActionInterface;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[CoversClass(ConfigurationService::class)]
#[CoversClass(PortalNodeConfigurationProcessorService::class)]
#[CoversClass(PackageContract::class)]
#[CoversClass(PortalContract::class)]
#[CoversClass(PortalExtensionContract::class)]
#[CoversClass(PortalExtensionCollection::class)]
#[CoversClass(PortalNodeKeyCollection::class)]
#[CoversClass(PortalNodeConfigurationGetCriteria::class)]
#[CoversClass(PortalNodeConfigurationGetResult::class)]
#[CoversClass(AbstractCollection::class)]
final class ConfigurationServiceTest extends TestCase
{
    public function testConfigurationTemplateLoading(): void
    {
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $registry = $this->createMock(PortalRegistryInterface::class);
        $registry->method('getPortal')->willReturn(new class() extends PortalContract {
            public function getConfigurationTemplate(): OptionsResolver
            {
                return parent::getConfigurationTemplate()
                    ->setDefined(['limit'])
                    ->setDefault('limit', 100);
            }
        });
        $registry->method('getPortalExtensions')->willReturn(new PortalExtensionCollection());
        $portalNodeConfigGet = $this->createMock(PortalNodeConfigurationGetActionInterface::class);
        $portalNodeConfigSet = $this->createMock(PortalNodeConfigurationSetActionInterface::class);
        $portalNodeConfigGet->method('get')->willReturn([
            new PortalNodeConfigurationGetResult($portalNodeKey, []),
        ]);

        $configService = new ConfigurationService(
            $registry,
            $portalNodeConfigGet,
            $portalNodeConfigSet,
            new PortalNodeConfigurationProcessorService([])
        );
        $config = $configService->getPortalNodeConfiguration($portalNodeKey);

        static::assertArrayHasKey('limit', $config);
        static::assertEquals(100, $config['limit']);
    }

    public function testConfigurationTemplateLoadingWithExtensionOverrides(): void
    {
        $registry = $this->createMock(PortalRegistryInterface::class);
        $registry->method('getPortal')->willReturn(new class() extends PortalContract {
            public function getConfigurationTemplate(): OptionsResolver
            {
                return parent::getConfigurationTemplate()
                    ->setDefined(['limit'])
                    ->setDefault('limit', 100);
            }
        });
        $registry->method('getPortalExtensions')->willReturn(new PortalExtensionCollection([
            new class() extends PortalExtensionContract {
                protected function supports(): string
                {
                    return PortalContract::class;
                }

                public function extendConfiguration(OptionsResolver $template): OptionsResolver
                {
                    return parent::extendConfiguration($template)
                        ->setDefined(['offset'])
                        ->setDefault('offset', 1000)
                        ->setDefault('limit', 200);
                }
            },
        ]));

        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $portalNodeConfigGet = $this->createMock(PortalNodeConfigurationGetActionInterface::class);
        $portalNodeConfigSet = $this->createMock(PortalNodeConfigurationSetActionInterface::class);
        $portalNodeConfigGet->method('get')->willReturn([
            new PortalNodeConfigurationGetResult($portalNodeKey, []),
        ]);

        $configService = new ConfigurationService(
            $registry,
            $portalNodeConfigGet,
            $portalNodeConfigSet,
            new PortalNodeConfigurationProcessorService([])
        );
        $config = $configService->getPortalNodeConfiguration($portalNodeKey);
        static::assertArrayHasKey('limit', $config);
        static::assertEquals(200, $config['limit']);
        static::assertArrayHasKey('offset', $config);
        static::assertEquals(1000, $config['offset']);
    }
}
