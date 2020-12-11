<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Portal;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract
 */
class ContractTest extends TestCase
{
    public function testExtendingPortalContract(): void
    {
        $portal = new class() extends PortalContract {
        };
        static::assertEquals(0, $portal->getEmitters()->count());
        static::assertEquals(0, $portal->getExplorers()->count());
        static::assertEquals(0, $portal->getReceivers()->count());
        static::assertCount(0, $portal->getConfigurationTemplate()->resolve());

        $services = $portal->getServices();
        static::assertArrayHasKey('portal', $services);
        static::assertEquals($portal, $services['portal']);
    }
}
