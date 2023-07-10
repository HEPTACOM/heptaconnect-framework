<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Reception;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverStack;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverStack
 */
final class ContractTest extends TestCase
{
    public function testExtendingReceiverContract(): void
    {
        $receiver = new class() extends ReceiverContract {
            public function receive(
                TypedDatasetEntityCollection $entities,
                ReceiveContextInterface $context,
                ReceiverStackInterface $stack
            ): iterable {
                yield from [];
            }

            public function supports(): string
            {
                return DatasetEntityContract::class;
            }
        };
        $receiveResult = \iterable_to_array($receiver->receive(
            new TypedDatasetEntityCollection(DatasetEntityContract::class, []),
            $this->createMock(ReceiveContextInterface::class),
            $this->createMock(ReceiverStackInterface::class)
        ));
        static::assertCount(0, $receiveResult);
        static::assertSame($receiver->supports(), DatasetEntityContract::class);
    }

    public function testAttachmentReadingReceiverContract(): void
    {
        $receiver = new class() extends ReceiverContract {
            public function supports(): string
            {
                return FirstEntity::class;
            }
        };
        $decoratingReceiver = new class() extends ReceiverContract {
            public function receive(
                TypedDatasetEntityCollection $entities,
                ReceiveContextInterface $context,
                ReceiverStackInterface $stack
            ): iterable {
                $key = 0;

                foreach ($this->receiveNextForExtends($stack, $entities, $context) as $entity) {
                    yield ++$key => $entity;
                }
            }

            public function supports(): string
            {
                return FirstEntity::class;
            }
        };
        static::assertSame(FirstEntity::class, $receiver->supports());
        static::assertSame(FirstEntity::class, $decoratingReceiver->supports());

        $context = $this->createMock(ReceiveContextInterface::class);
        $entities = new TypedDatasetEntityCollection(FirstEntity::class, [new FirstEntity()]);

        $singleStack = [$receiver];
        $stackResult = \iterable_to_array((new ReceiverStack($singleStack))->next($entities, $context));
        static::assertCount(\count($singleStack), $stackResult);

        $decoratedStack = [$receiver, $decoratingReceiver];
        $stackResult = \iterable_to_array((new ReceiverStack($decoratedStack))->next($entities, $context));
        static::assertCount(\count($decoratedStack), $stackResult);
    }
}
