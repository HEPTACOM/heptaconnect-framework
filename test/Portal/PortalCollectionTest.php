<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Portal;

use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerContract;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\PortalCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Portal\PortalCollection
 */
class PortalCollectionTest extends TestCase
{
    public function testEmitterAggregation(): void
    {
        $ext1 = $this->createMock(PortalContract::class);
        $ext1->method('getEmitters')->willReturn(new EmitterCollection([
            $this->createMock(EmitterContract::class),
        ]));

        $ext2 = $this->createMock(PortalContract::class);
        $ext2->method('getEmitters')->willReturn(new EmitterCollection([
            $this->createMock(EmitterContract::class),
        ]));

        $ext3 = $this->createMock(PortalContract::class);
        $ext3->method('getEmitters')->willReturn(new EmitterCollection([
            $this->createMock(EmitterContract::class),
        ]));

        $collection = new PortalCollection([$ext1, $ext2, $ext3]);
        static::assertCount(3, $collection->getEmitters());
    }

    public function testExplorerAggregation(): void
    {
        $ext1 = $this->createMock(PortalContract::class);
        $ext1->method('getExplorers')->willReturn(new ExplorerCollection([
            $this->createMock(ExplorerContract::class),
        ]));

        $ext2 = $this->createMock(PortalContract::class);
        $ext2->method('getExplorers')->willReturn(new ExplorerCollection([
            $this->createMock(ExplorerContract::class),
        ]));

        $ext3 = $this->createMock(PortalContract::class);
        $ext3->method('getExplorers')->willReturn(new ExplorerCollection([
            $this->createMock(ExplorerContract::class),
        ]));

        $collection = new PortalCollection([$ext1, $ext2, $ext3]);
        static::assertCount(3, $collection->getExplorers());
    }

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
