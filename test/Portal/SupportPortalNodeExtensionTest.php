<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Portal;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Support\AbstractPortalExtension;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Support\AbstractPortalExtension
 */
class SupportPortalNodeExtensionTest extends TestCase
{
    public function testSupportsSomething(): void
    {
        $extension = new class() extends AbstractPortalExtension {
        };
        static::assertTrue(\is_a($extension->supports(), PortalContract::class, true));
    }

    public function testHasEmitterDecorators(): void
    {
        $extension = new class() extends AbstractPortalExtension {
        };
        static::assertCount(0, $extension->getEmitterDecorators());
    }

    public function testHasExplorerDecorators(): void
    {
        $extension = new class() extends AbstractPortalExtension {
        };
        static::assertCount(0, $extension->getExplorerDecorators());
    }

    public function testHasReceiverDecorators(): void
    {
        $extension = new class() extends AbstractPortalExtension {
        };
        static::assertCount(0, $extension->getReceiverDecorators());
    }
}
