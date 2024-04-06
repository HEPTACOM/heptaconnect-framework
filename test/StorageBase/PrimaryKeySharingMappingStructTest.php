<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test;

use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\PrimaryKeySharingMappingStruct;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\Simple;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Storage\Base\PrimaryKeySharingMappingStruct
 */
final class PrimaryKeySharingMappingStructTest extends TestCase
{
    public function testPrimaryKeySharing(): void
    {
        $struct = new PrimaryKeySharingMappingStruct(
            Simple::class(),
            null,
            $this->createMock(PortalNodeKeyInterface::class),
            $this->createMock(MappingNodeKeyInterface::class),
        );
        $struct->setForeignKey('foobar');

        $entity1 = new Simple();
        $entity1->setPrimaryKey($struct->getForeignKey());
        $entity2 = new Simple();
        $entity2->setPrimaryKey($struct->getForeignKey());

        $struct->addOwner($entity1);
        $struct->addOwner($entity2);

        $entity1->setPrimaryKey('fazzlebedazzle');
        static::assertSame($entity1->getPrimaryKey(), $entity2->getPrimaryKey());
    }

    public function testSerialization(): void
    {
        $struct = new PrimaryKeySharingMappingStruct(
            Simple::class(),
            null,
            $this->createMock(PortalNodeKeyInterface::class),
            $this->createMock(MappingNodeKeyInterface::class),
        );
        $struct->setForeignKey('foobar');

        $entity1 = new Simple();
        $entity1->setPrimaryKey($struct->getForeignKey());
        $entity2 = new Simple();
        $entity2->setPrimaryKey($struct->getForeignKey());

        $struct->addOwner($entity1);
        $struct->addOwner($entity2);

        $serialized = \serialize([$entity1, $entity2]);
        [$entity3, $entity4] = \unserialize($serialized);

        $entity3->setPrimaryKey('fazzlebedazzle');
        static::assertSame($entity3->getPrimaryKey(), $entity4->getPrimaryKey());
    }
}
