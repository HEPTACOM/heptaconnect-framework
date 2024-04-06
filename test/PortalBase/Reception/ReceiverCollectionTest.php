<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Reception;

use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\SecondEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection
 */
final class ReceiverCollectionTest extends TestCase
{
    public function testBySupport(): void
    {
        $collection = new ReceiverCollection();

        $collection->push([
            $this->getReceiver(FirstEntity::class),
            $this->getReceiver(SecondEntity::class),
            $this->getReceiver(FirstEntity::class),
            $this->getReceiver(SecondEntity::class),
            $this->getReceiver(FirstEntity::class),
        ]);
        static::assertNotEmpty($collection->bySupport(FirstEntity::class()));
        static::assertNotEmpty($collection->bySupport(SecondEntity::class()));
        static::assertCount(3, $collection->bySupport(FirstEntity::class()));
        static::assertCount(2, $collection->bySupport(SecondEntity::class()));
    }

    private function getReceiver(string $support): ReceiverContract
    {
        $receiver = $this->createMock(ReceiverContract::class);
        $receiver->expects(static::any())->method('supports')->willReturn($support);

        return $receiver;
    }
}
