<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntity;
use Heptacom\HeptaConnect\Dataset\Base\Support\TrackedEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityTrackerContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\TrackedEntityCollection
 */
class TrackedEntityCollectionTest extends TestCase
{
    use ProvidesInvalidTestsData;

    /**
     * @dataProvider provideValidTestCases
     */
    public function testInsertTypeInTypeCollection(DatasetEntityInterface $entity): void
    {
        $collection = new TrackedEntityCollection();
        $collection->push([$entity]);
        static::assertCount(1, $collection);
        static::assertEquals($entity, $collection[0]);
    }

    /**
     * @dataProvider provideInvalidTestCases
     */
    public function testInsertOtherTypeInTypeCollection($item): void
    {
        $collection = new TrackedEntityCollection();
        $collection->push([$item]);
        static::assertCount(0, $collection);
    }

    public function provideValidTestCases(): iterable
    {
        yield [new SerializationDatasetEntity()];
        yield [new class() extends DatasetEntity {
        }];
    }
}
