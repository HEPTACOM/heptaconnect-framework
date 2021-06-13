<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Portal;

use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalCollection
 */
class PortalCollectionTest extends TestCase
{
    public function testReceiverAggregation(): void
    {
        $ext1 = $this->createMock(PortalContract::class);
        $ext1->method('getReceivers')->willReturn(new ReceiverCollection([
            $this->createMock(ReceiverContract::class),
        ]));

        $ext2 = $this->createMock(PortalContract::class);
        $ext2->method('getReceivers')->willReturn(new ReceiverCollection([
            $this->createMock(ReceiverContract::class),
        ]));

        $ext3 = $this->createMock(PortalContract::class);
        $ext3->method('getReceivers')->willReturn(new ReceiverCollection([
            $this->createMock(ReceiverContract::class),
        ]));

        $collection = new PortalCollection([$ext1, $ext2, $ext3]);
        static::assertCount(3, $collection->getReceivers());
    }
}
