<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\PrimaryKeySharingMappingStruct;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\Simple;
use Heptacom\HeptaConnect\Utility\Attachment\AttachmentCollection;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(PrimaryKeySharingMappingStruct::class)]
#[CoversClass(AttachmentCollection::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
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
