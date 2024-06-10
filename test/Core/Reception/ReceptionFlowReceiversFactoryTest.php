<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Reception;

use Heptacom\HeptaConnect\Core\Reception\LockingReceiver;
use Heptacom\HeptaConnect\Core\Reception\ReceptionFlowReceiversFactory;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(LockingReceiver::class)]
#[CoversClass(ReceptionFlowReceiversFactory::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(ReceiverCollection::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
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
