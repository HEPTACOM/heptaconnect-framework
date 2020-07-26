<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeInterface;
use Heptacom\HeptaConnect\Portal\Base\Support\AbstractPortalNodeExtension;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Support\AbstractPortalNodeExtension
 */
class SupportPortalNodeExtensionTest extends TestCase
{
    public function testSupportsSomething(): void
    {
        $extension = new class() extends AbstractPortalNodeExtension {
        };
        static::assertTrue(\is_a($extension->supports(), PortalNodeInterface::class, true));
    }

    public function testHasEmitterDecorators(): void
    {
        $extension = new class() extends AbstractPortalNodeExtension {
        };
        static::assertCount(0, $extension->getEmitterDecorators());
    }

    public function testHasExplorerDecorators(): void
    {
        $extension = new class() extends AbstractPortalNodeExtension {
        };
        static::assertCount(0, $extension->getExplorerDecorators());
    }

    public function testHasReceiverDecorators(): void
    {
        $extension = new class() extends AbstractPortalNodeExtension {
        };
        static::assertCount(0, $extension->getReceiverDecorators());
    }
}
