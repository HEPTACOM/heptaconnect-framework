<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Storage\Base\Test;

use Heptacom\HeptaConnect\Storage\Base\PrimaryKeySharingMappingStruct;
use Heptacom\HeptaConnect\Storage\Base\Test\Fixture\Simple;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Storage\Base\PrimaryKeySharingMappingStruct
 */
class PrimaryKeySharingMappingStructTest extends TestCase
{
    public function testPrimaryKeySharing(): void
    {
        $struct = new PrimaryKeySharingMappingStruct();
        $struct->setForeignKey('foobar');
        $struct->setDatasetEntityClassName(Simple::class);

        $entity1 = new Simple();
        $entity1->setPrimaryKey($struct->getForeignKey());
        $entity2 = new Simple();
        $entity2->setPrimaryKey($struct->getForeignKey());

        $struct->addOwner($entity1);
        $struct->addOwner($entity2);

        $entity1->setPrimaryKey('fazzlebedazzle');
        static::assertSame($entity1->getPrimaryKey(), $entity2->getPrimaryKey());
    }
}
