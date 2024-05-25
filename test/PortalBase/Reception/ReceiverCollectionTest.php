<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Reception;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\SecondEntity;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(ReceiverContract::class)]
#[CoversClass(ReceiverCollection::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
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
