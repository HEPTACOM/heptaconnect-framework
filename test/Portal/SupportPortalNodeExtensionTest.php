<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Portal;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract
 */
class SupportPortalNodeExtensionTest extends TestCase
{
    public function testSupportsSomething(): void
    {
        $extension = new class() extends PortalExtensionContract {
            public function supports(): string
            {
                return PortalContract::class;
            }
        };
        static::assertTrue(\is_a($extension->supports(), PortalContract::class, true));
    }

    public function testHasEmitterDecorators(): void
    {
        $extension = new class() extends PortalExtensionContract {
            public function supports(): string
            {
                return PortalContract::class;
            }
        };
        static::assertCount(0, $extension->getEmitterDecorators());
    }

    public function testHasExplorerDecorators(): void
    {
        $extension = new class() extends PortalExtensionContract {
            public function supports(): string
            {
                return PortalContract::class;
            }
        };
        static::assertCount(0, $extension->getExplorerDecorators());
    }

    public function testHasReceiverDecorators(): void
    {
        $extension = new class() extends PortalExtensionContract {
            public function supports(): string
            {
                return PortalContract::class;
            }
        };
        static::assertCount(0, $extension->getReceiverDecorators());
    }

    public function testConfigurationExtension(): void
    {
        $extension = new class() extends PortalExtensionContract {
            public function supports(): string
            {
                return PortalContract::class;
            }
        };
        static::assertCount(0, $extension->extendConfiguration(new OptionsResolver())->resolve());
    }
}
