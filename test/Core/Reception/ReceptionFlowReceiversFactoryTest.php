<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Reception;

use Heptacom\HeptaConnect\Core\Reception\LockingReceiver;
use Heptacom\HeptaConnect\Core\Reception\ReceptionFlowReceiversFactory;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Reception\LockingReceiver
 * @covers \Heptacom\HeptaConnect\Core\Reception\ReceptionFlowReceiversFactory
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection
 */
final class ReceptionFlowReceiversFactoryTest extends TestCase
{
    public function testCollectionContainsExpectedServices(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $entityType = FooBarEntity::class();

        $factory = new ReceptionFlowReceiversFactory($logger);
        $receivers = $factory->createReceivers($portalNodeKey, $entityType);
        static::assertCount(1, $receivers);
        static::assertInstanceOf(LockingReceiver::class, $receivers[0]);
    }
}
