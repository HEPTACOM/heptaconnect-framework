<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test;

use Heptacom\HeptaConnect\Core\Component\LogMessage;
use Heptacom\HeptaConnect\Core\Reception\Contract\ReceiveContextFactoryInterface;
use Heptacom\HeptaConnect\Core\Reception\Contract\ReceiverStackBuilderFactoryInterface;
use Heptacom\HeptaConnect\Core\Reception\Contract\ReceiverStackBuilderInterface;
use Heptacom\HeptaConnect\Core\Reception\Contract\ReceiverStackProcessorInterface;
use Heptacom\HeptaConnect\Core\Reception\Contract\ReceptionFlowReceiversFactoryInterface;
use Heptacom\HeptaConnect\Core\Reception\ReceiverStack;
use Heptacom\HeptaConnect\Core\Reception\ReceiveService;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(LogMessage::class)]
#[CoversClass(ReceiveService::class)]
#[CoversClass(ReceiverStack::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(DatasetEntityCollection::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(TypedDatasetEntityCollection::class)]
#[CoversClass(MappedDatasetEntityCollection::class)]
#[CoversClass(MappedDatasetEntityStruct::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
final class ReceiveServiceTest extends TestCase
{
    #[DataProvider('provideReceiveCount')]
    public function testReceiveCount(int $count): void
    {
        $receiveContextFactory = $this->createMock(ReceiveContextFactoryInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);

        $stack = $this->createMock(ReceiverStackInterface::class);
        $stack->expects(static::never())->method('next')->willReturn([]);

        $stackBuilder = $this->createMock(ReceiverStackBuilderInterface::class);
        $stackBuilder->method('build')->willReturn($stack);
        $stackBuilder->method('pushSource')->willReturnSelf();
        $stackBuilder->method('pushDecorators')->willReturnSelf();
        $stackBuilder->expects($count > 0 ? static::atLeastOnce() : static::never())->method('isEmpty')->willReturn(true);
        $stackBuilderFactory = $this->createMock(ReceiverStackBuilderFactoryInterface::class);
        $stackBuilderFactory->method('createReceiverStackBuilder')->willReturn($stackBuilder);

        $receiveService = new ReceiveService(
            $receiveContextFactory,
            $logger,
            $storageKeyGenerator,
            $stackBuilderFactory,
            $this->createMock(ReceiverStackProcessorInterface::class),
            $this->createMock(ReceptionFlowReceiversFactoryInterface::class),
        );
        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $receiveService->receive(
            new TypedDatasetEntityCollection(FooBarEntity::class(), \array_fill(0, $count, new FooBarEntity())),
            $portalNodeKey
        );
    }

    #[DataProvider('provideReceiveCount')]
    public function testMissingReceiver(int $count): void
    {
        $receiveContextFactory = $this->createMock(ReceiveContextFactoryInterface::class);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($count > 0 ? static::atLeastOnce() : static::never())
            ->method('critical')
            ->with(LogMessage::RECEIVE_NO_RECEIVER_FOR_TYPE());

        $stack = new ReceiverStack([], $this->createMock(LoggerInterface::class));
        $stackBuilder = $this->createMock(ReceiverStackBuilderInterface::class);
        $stackBuilder->method('build')->willReturn($stack);
        $stackBuilder->method('pushSource')->willReturnSelf();
        $stackBuilder->method('pushDecorators')->willReturnSelf();
        $stackBuilder->expects($count > 0 ? static::atLeastOnce() : static::never())->method('isEmpty')->willReturn(true);
        $stackBuilderFactory = $this->createMock(ReceiverStackBuilderFactoryInterface::class);
        $stackBuilderFactory->method('createReceiverStackBuilder')->willReturn($stackBuilder);

        $portalNodeKey = $this->createMock(PortalNodeKeyInterface::class);
        $storageKeyGenerator = $this->createMock(StorageKeyGeneratorContract::class);

        $receiveService = new ReceiveService(
            $receiveContextFactory,
            $logger,
            $storageKeyGenerator,
            $stackBuilderFactory,
            $this->createMock(ReceiverStackProcessorInterface::class),
            $this->createMock(ReceptionFlowReceiversFactoryInterface::class),
        );
        $receiveService->receive(
            new TypedDatasetEntityCollection(FooBarEntity::class(), \array_fill(0, $count, new FooBarEntity())),
            $portalNodeKey
        );
    }

    /**
     * @return iterable<array-key, array<array-key, int>>
     */
    public static function provideReceiveCount(): iterable
    {
        yield [0];
        yield [1];
        yield [2];
        yield [3];
        yield [4];
        yield [5];
        yield [6];
        yield [7];
        yield [8];
        yield [9];
        yield [10];
    }
}
