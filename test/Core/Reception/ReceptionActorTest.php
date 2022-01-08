<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Reception;

use Heptacom\HeptaConnect\Core\Component\LogMessage;
use Heptacom\HeptaConnect\Core\Reception\Contract\ReceiverStackBuilderFactoryInterface;
use Heptacom\HeptaConnect\Core\Reception\Contract\ReceiverStackBuilderInterface;
use Heptacom\HeptaConnect\Core\Reception\ReceptionActor;
use Heptacom\HeptaConnect\Core\Test\Fixture\FooBarEntity;
use Heptacom\HeptaConnect\Core\Test\Fixture\ThrowReceiver;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverStack;
use Heptacom\HeptaConnect\Portal\Base\Reception\Support\PostProcessorDataBag;
use Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \Heptacom\HeptaConnect\Core\Component\LogMessage
 * @covers \Heptacom\HeptaConnect\Core\Event\PostReceptionEvent
 * @covers \Heptacom\HeptaConnect\Core\Reception\ReceptionActor
 * @covers \Heptacom\HeptaConnect\Core\Reception\Support\PrimaryKeyChangesAttachable
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverStack
 * @covers \Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract
 */
class ReceptionActorTest extends TestCase
{
    /**
     * @dataProvider provideEmitCount
     */
    public function testActingFails(int $count): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($count > 0 ? static::atLeastOnce() : static::never())
            ->method('critical')
            ->with(LogMessage::RECEIVE_NO_THROW());

        $stack = new ReceiverStack([new ThrowReceiver()]);
        $stackBuilder = $this->createMock(ReceiverStackBuilderInterface::class);
        $stackBuilder->method('build')->willReturn($stack);
        $stackBuilder->method('pushSource')->willReturnSelf();
        $stackBuilder->method('pushDecorators')->willReturnSelf();
        $stackBuilder->method('isEmpty')->willReturn(false);
        $stackBuilderFactory = $this->createMock(ReceiverStackBuilderFactoryInterface::class);
        $stackBuilderFactory->method('createReceiverStackBuilder')->willReturn($stackBuilder);

        $entity = $this->createMock(FooBarEntity::class);

        $receptionActor = new ReceptionActor(
            $logger,
            new DeepObjectIteratorContract(),
        );

        $context = $this->createMock(ReceiveContextInterface::class);
        $context->method('getPostProcessingBag')->willReturn(new PostProcessorDataBag());

        $receptionActor->performReception(
            new TypedDatasetEntityCollection(FooBarEntity::class, \array_fill(0, $count, $entity)),
            $stack,
            $context
        );
    }

    /**
     * @return iterable<array-key, array<array-key, int>>
     */
    public function provideEmitCount(): iterable
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
