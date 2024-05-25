<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Portal;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PackageContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalType;
use Heptacom\HeptaConnect\Portal\Base\Portal\SupportedPortalType;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\Portal;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

#[CoversClass(PackageContract::class)]
#[CoversClass(PortalContract::class)]
#[CoversClass(PortalExtensionContract::class)]
#[CoversClass(PortalType::class)]
#[CoversClass(SupportedPortalType::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
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
