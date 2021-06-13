<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Portal;

use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalExtensionContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalExtensionCollection
 */
class PortalNodeExtensionCollectionTest extends TestCase
{
    public function testExplorerDecoratorAggregation(): void
    {
        $ext1 = $this->createMock(PortalExtensionContract::class);
        $ext1->method('getExplorerDecorators')->willReturn(new ExplorerCollection([
            $this->createMock(ExplorerContract::class),
        ]));

        $ext2 = $this->createMock(PortalExtensionContract::class);
        $ext2->method('getExplorerDecorators')->willReturn(new ExplorerCollection([
            $this->createMock(ExplorerContract::class),
        ]));

        $ext3 = $this->createMock(PortalExtensionContract::class);
        $ext3->method('getExplorerDecorators')->willReturn(new ExplorerCollection([
            $this->createMock(ExplorerContract::class),
        ]));

        $collection = new PortalExtensionCollection([$ext1, $ext2, $ext3]);
        static::assertCount(3, $collection->getExplorerDecorators());
    }

    public function testReceiverDecoratorAggregation(): void
    {
        $ext1 = $this->createMock(PortalExtensionContract::class);
        $ext1->method('getReceiverDecorators')->willReturn(new ReceiverCollection([
            $this->createMock(ReceiverContract::class),
        ]));

        $ext2 = $this->createMock(PortalExtensionContract::class);
        $ext2->method('getReceiverDecorators')->willReturn(new ReceiverCollection([
            $this->createMock(ReceiverContract::class),
        ]));

        $ext3 = $this->createMock(PortalExtensionContract::class);
        $ext3->method('getReceiverDecorators')->willReturn(new ReceiverCollection([
            $this->createMock(ReceiverContract::class),
        ]));

        $collection = new PortalExtensionCollection([$ext1, $ext2, $ext3]);
        static::assertCount(3, $collection->getReceiverDecorators());
    }
}
