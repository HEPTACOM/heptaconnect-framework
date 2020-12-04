<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Dataset\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Support\DatasetEntityTracker;
use Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityTrackerContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntity
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\DatasetEntityTracker
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Test\Fixture\SerializationDatasetEntity
 */
class DatasetEntityTrackerTest extends TestCase
{
    private DatasetEntityTracker $tracker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tracker = DatasetEntityTracker::instance();
        $this->tracker->listen();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->tracker->retrieve();
    }

    /**
     * @dataProvider provideTrackableEntityClasses
     */
    public function testTrackingNewEntities(string ...$entityClasses): void
    {
        foreach ($entityClasses as $entityClass) {
            new $entityClass();
        }

        static::assertCount(\count($entityClasses), $this->tracker->retrieve());
    }

    /**
     * @dataProvider provideTrackableEntityClasses
     */
    public function testTrackingNewEntitiesOnlyOnLatestStack(string ...$entityClasses): void
    {
        $this->tracker->listen();
        $this->tracker->listen();

        foreach ($entityClasses as $entityClass) {
            new $entityClass();
        }

        static::assertCount(\count($entityClasses), $this->tracker->retrieve());
        static::assertCount(0, $this->tracker->retrieve());
    }

    /**
     * @dataProvider provideTrackableEntityClasses
     */
    public function testTrackingNewEntitiesOnlyOnWhenAllowed(string ...$entityClasses): void
    {
        foreach ($entityClasses as $entityClass) {
            $this->tracker->deny($entityClass);
            new $entityClass();
            static::assertCount(0, $this->tracker->retrieve());
            $this->tracker->listen();

            $this->tracker->allow($entityClass);
            new $entityClass();
            static::assertCount(1, $this->tracker->retrieve());
            $this->tracker->listen();
        }
    }

    public function provideTrackableEntityClasses(): iterable
    {
        yield [SerializationDatasetEntity::class];
        yield [SerializationDatasetEntity::class, SerializationDatasetEntity::class];
        yield [SerializationDatasetEntity::class, SerializationDatasetEntity::class, SerializationDatasetEntity::class];
    }
}
