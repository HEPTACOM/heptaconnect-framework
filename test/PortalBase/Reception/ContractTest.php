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
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\ClassStringReferenceContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Contract\SubtypeClassStringContract
 * @covers \Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\Support\AbstractObjectCollection
 * @covers \Heptacom\HeptaConnect\Dataset\Base\EntityType
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

            protected function supports(): string
            {
                return FirstEntity::class;
            }
        };
        static::assertCount(0, $receiver->receive(
            new TypedDatasetEntityCollection((new class() extends DatasetEntityContract {
            })::class(), []),
            $this->createMock(ReceiveContextInterface::class),
            $this->createMock(ReceiverStackInterface::class)
        ));
        static::assertTrue($receiver->getSupportedEntityType()->same(FirstEntity::class()));
    }

    public function testExtendingReceiverContractLikeIn0Dot9(): void
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
                return FirstEntity::class;
            }
        };
        static::assertCount(0, $receiver->receive(
            new TypedDatasetEntityCollection((new class() extends DatasetEntityContract {
            })::class(), []),
            $this->createMock(ReceiveContextInterface::class),
            $this->createMock(ReceiverStackInterface::class)
        ));
        static::assertTrue($receiver->getSupportedEntityType()->same(FirstEntity::class()));
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
                return $this->receiveNextForExtends($stack, $entities, $context);
            }

            public function supports(): string
            {
                return FirstEntity::class;
            }
        };
        static::assertTrue(FirstEntity::class()->same($receiver->getSupportedEntityType()));
        static::assertTrue(FirstEntity::class()->same($decoratingReceiver->getSupportedEntityType()));

        $context = $this->createMock(ReceiveContextInterface::class);
        $entities = new TypedDatasetEntityCollection(FirstEntity::class(), [new FirstEntity()]);

        $singleStack = [$receiver];
        static::assertCount(\count($singleStack), (new ReceiverStack($singleStack))->next($entities, $context));

        $decoratedStack = [$receiver, $decoratingReceiver];
        static::assertCount(\count($decoratedStack), (new ReceiverStack($decoratedStack))->next($entities, $context));
    }
}
