<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Storage;

use Heptacom\HeptaConnect\Core\Storage\PrimaryKeyToEntityHydrator;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Utility\Collection\Scalar\StringCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Core\Storage\PrimaryKeyToEntityHydrator
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Utility\Attachment\AttachmentAwareTrait
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection
 */
final class PrimaryKeyToEntityHydratorTest extends TestCase
{
    /**
     * @dataProvider providePrimaryKeys
     */
    public function testCountHydration(StringCollection $primaryKeys): void
    {
        $hydrator = new PrimaryKeyToEntityHydrator();
        $result = $hydrator->hydrate(FooBarEntity::class(), $primaryKeys);

        static::assertTrue($result->getEntityType()->equals(FooBarEntity::class()));
        static::assertCount($primaryKeys->count(), $result);
    }

    public function providePrimaryKeys(): iterable
    {
        $keys = new StringCollection();
        yield [clone $keys];
        $keys->push(['062b536f-a718-4a07-9035-2482c0e64cc8']);
        yield [clone $keys];
        $keys->push(['6aeb0bc9-7cfd-431f-bfc7-36c71a945858']);
        yield [clone $keys];
        $keys->push(['8c82abab-c698-43d1-b3cc-7b8f3a1adc6d']);
        yield [clone $keys];
        $keys->push(['c409e07e-03cc-4592-a2b2-f552ba4a5c3f']);
        yield [clone $keys];
        $keys->push(['354f54be-be16-4b1f-972e-30fe01131a64']);
        yield [clone $keys];
        $keys->push(['e6691e25-6b46-4e8b-91bb-1c3129c5021d']);
        yield [clone $keys];
        $keys->push(['54b2c613-3c16-448e-8442-5e84438bfce6']);
        yield [clone $keys];
        $keys->push(['f2c99b20-e51a-4d2e-a7aa-23f6914499c9']);
        yield [clone $keys];
    }
}
