<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalNodeExtensionInterface;
use Heptacom\HeptaConnect\Portal\Base\PortalNodeExtensionCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Exploration\ExplorerCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\PortalNodeExtensionCollection
 */
class PortalNodeExtensionCollectionTest extends TestCase
{
    public function testEmitterDecoratorAggregation(): void
    {
        $ext1 = $this->createMock(PortalNodeExtensionInterface::class);
        $ext1->method('getEmitterDecorators')->willReturn(new EmitterCollection([
            $this->createMock(EmitterInterface::class),
        ]));

        $ext2 = $this->createMock(PortalNodeExtensionInterface::class);
        $ext2->method('getEmitterDecorators')->willReturn(new EmitterCollection([
            $this->createMock(EmitterInterface::class),
        ]));

        $ext3 = $this->createMock(PortalNodeExtensionInterface::class);
        $ext3->method('getEmitterDecorators')->willReturn(new EmitterCollection([
            $this->createMock(EmitterInterface::class),
        ]));

        $collection = new PortalNodeExtensionCollection([$ext1, $ext2, $ext3]);
        static::assertCount(3, $collection->getEmitterDecorators());
    }

    public function testExplorerDecoratorAggregation(): void
    {
        $ext1 = $this->createMock(PortalNodeExtensionInterface::class);
        $ext1->method('getExplorerDecorators')->willReturn(new ExplorerCollection([
            $this->createMock(ExplorerInterface::class),
        ]));

        $ext2 = $this->createMock(PortalNodeExtensionInterface::class);
        $ext2->method('getExplorerDecorators')->willReturn(new ExplorerCollection([
            $this->createMock(ExplorerInterface::class),
        ]));

        $ext3 = $this->createMock(PortalNodeExtensionInterface::class);
        $ext3->method('getExplorerDecorators')->willReturn(new ExplorerCollection([
            $this->createMock(ExplorerInterface::class),
        ]));

        $collection = new PortalNodeExtensionCollection([$ext1, $ext2, $ext3]);
        static::assertCount(3, $collection->getExplorerDecorators());
    }

    public function testReceiverDecoratorAggregation(): void
    {
        $ext1 = $this->createMock(PortalNodeExtensionInterface::class);
        $ext1->method('getReceiverDecorators')->willReturn(new ReceiverCollection([
            $this->createMock(ReceiverInterface::class),
        ]));

        $ext2 = $this->createMock(PortalNodeExtensionInterface::class);
        $ext2->method('getReceiverDecorators')->willReturn(new ReceiverCollection([
            $this->createMock(ReceiverInterface::class),
        ]));

        $ext3 = $this->createMock(PortalNodeExtensionInterface::class);
        $ext3->method('getReceiverDecorators')->willReturn(new ReceiverCollection([
            $this->createMock(ReceiverInterface::class),
        ]));

        $collection = new PortalNodeExtensionCollection([$ext1, $ext2, $ext3]);
        static::assertCount(3, $collection->getReceiverDecorators());
    }
}
