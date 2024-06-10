<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Emission;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterContract;
use Heptacom\HeptaConnect\Portal\Base\Emission\EmitterCollection;
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
#[CoversClass(EmitterContract::class)]
#[CoversClass(EmitterCollection::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
final class EmitterCollectionTest extends TestCase
{
    public function testBySupport(): void
    {
        $collection = new EmitterCollection();

        $collection->push([
            $this->getEmitter(FirstEntity::class),
            $this->getEmitter(SecondEntity::class),
            $this->getEmitter(FirstEntity::class),
            $this->getEmitter(SecondEntity::class),
            $this->getEmitter(FirstEntity::class),
        ]);
        static::assertNotEmpty($collection->bySupport(FirstEntity::class()));
        static::assertNotEmpty($collection->bySupport(SecondEntity::class()));
        static::assertCount(3, $collection->bySupport(FirstEntity::class()));
        static::assertCount(2, $collection->bySupport(SecondEntity::class()));
    }

    private function getEmitter(string $support): EmitterContract
    {
        $emitter = $this->createMock(EmitterContract::class);
        $emitter->expects(static::any())->method('supports')->willReturn($support);

        return $emitter;
    }
}
