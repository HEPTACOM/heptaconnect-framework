<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Support;

use Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntityCollectionWithOtherProperties;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\SecondEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract
 */
final class DeepObjectIteratorTest extends TestCase
{
    public function testDeepObjectIterator(): void
    {
        $deepObjectIterator = new DeepObjectIteratorContract();

        $firstEntity = new FirstEntity();
        $secondEntity = new SecondEntity();
        $firstEntity->attach($secondEntity);

        $list1 = \iterable_to_array(
            $deepObjectIterator->iterate($firstEntity)
        );

        static::assertContains($firstEntity, $list1);
        static::assertContains($secondEntity, $list1);

        $firstEntityA = new FirstEntity();
        $firstEntityB = new FirstEntity();
        $firstEntityC = new FirstEntity();

        $firstEntityCollection = new FirstEntityCollection([
            $firstEntityA,
            $firstEntityB,
            $firstEntityC,
        ]);

        $list2 = \iterable_to_array(
            $deepObjectIterator->iterate($firstEntityCollection)
        );

        static::assertContains($firstEntityA, $list2);
        static::assertContains($firstEntityB, $list2);
        static::assertContains($firstEntityC, $list2);
        static::assertContains($firstEntityCollection, $list2);

        $firstEntityCollectionWithOtherProperties = new FirstEntityCollectionWithOtherProperties([
            $firstEntityA,
            $firstEntityB,
            $firstEntityC,
        ]);

        $firstEntityCollectionWithOtherProperties->setSecondEntity($secondEntity);

        $list3 = \iterable_to_array(
            $deepObjectIterator->iterate($firstEntityCollectionWithOtherProperties)
        );

        static::assertContains($firstEntityA, $list3);
        static::assertContains($firstEntityB, $list3);
        static::assertContains($firstEntityC, $list3);
        static::assertContains($secondEntity, $list3);
        static::assertContains($firstEntityCollectionWithOtherProperties, $list3);
    }
}
