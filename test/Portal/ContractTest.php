<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Portal;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract
 */
class ContractTest extends TestCase
{
    public function testExtendingPortalContract(): void
    {
        $portal = new class() extends PortalContract {
        };
        static::assertCount(0, $portal->getConfigurationTemplate()->resolve());
    }

    public function testOverridingPathOfPortalContract(): void
    {
        $portal = new class() extends PortalContract {
            public function getPsr4(): array
            {
                return ['__NAMESPACE__' => '__DIR__'];
            }

            public function getContainerConfigurationPath(): string
            {
                return '__DIR__';
            }

            public function getFlowComponentsPath(): string
            {
                return '__DIR__';
            }
        };

        static::assertEquals(['__NAMESPACE__' => '__DIR__'], $portal->getPsr4());
        static::assertEquals('__DIR__', $portal->getContainerConfigurationPath());
        static::assertEquals('__DIR__', $portal->getFlowComponentsPath());
    }

    public function testExtendingPortalExtensionContract(): void
    {
        $portalExt = new class() extends PortalExtensionContract {
            public function supports(): string
            {
                return self::class;
            }
        };
        static::assertCount(0, $portalExt->extendConfiguration(new OptionsResolver())->resolve());
    }

    public function testOverridingPathOfPortalExtensionContract(): void
    {
        $portalExt = new class() extends PortalExtensionContract {
            public function supports(): string
            {
                return self::class;
            }

            public function getPsr4(): array
            {
                return ['__NAMESPACE__' => '__DIR__'];
            }

            public function getContainerConfigurationPath(): string
            {
                return '__DIR__';
            }

            public function getFlowComponentsPath(): string
            {
                return '__DIR__';
            }
        };

        static::assertEquals(['__NAMESPACE__' => '__DIR__'], $portalExt->getPsr4());
        static::assertEquals('__DIR__', $portalExt->getContainerConfigurationPath());
        static::assertEquals('__DIR__', $portalExt->getFlowComponentsPath());
    }
}
