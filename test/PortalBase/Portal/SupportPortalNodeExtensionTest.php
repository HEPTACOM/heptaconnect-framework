<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Portal;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\Portal;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalType
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\SupportedPortalType
 */
final class SupportPortalNodeExtensionTest extends TestCase
{
    public function testSupportsSomething(): void
    {
        $extension = new class() extends PortalExtensionContract {
            protected function supports(): string
            {
                return Portal::class;
            }
        };

        static::assertTrue($extension->getSupportedPortal()->equals(Portal::class()));
    }

    public function testConfigurationExtension(): void
    {
        $extension = new class() extends PortalExtensionContract {
            protected function supports(): string
            {
                return Portal::class;
            }
        };
        static::assertCount(0, $extension->extendConfiguration(new OptionsResolver())->resolve());
    }
}
