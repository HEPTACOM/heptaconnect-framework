<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Reception;

use Heptacom\HeptaConnect\Core\Reception\ReceiverStack;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverCollection;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\ClassStringReferenceContract;
use Heptacom\HeptaConnect\Utility\ClassString\Contract\SubtypeClassStringContract;
use Heptacom\HeptaConnect\Utility\Collection\AbstractCollection;
use Heptacom\HeptaConnect\Utility\Collection\AbstractObjectCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversClass(ReceiverStack::class)]
#[CoversClass(DatasetEntityContract::class)]
#[CoversClass(DatasetEntityCollection::class)]
#[CoversClass(EntityType::class)]
#[CoversClass(TypedDatasetEntityCollection::class)]
#[CoversClass(ReceiverContract::class)]
#[CoversClass(ReceiverCollection::class)]
#[CoversClass(ClassStringContract::class)]
#[CoversClass(ClassStringReferenceContract::class)]
#[CoversClass(SubtypeClassStringContract::class)]
#[CoversClass(AbstractCollection::class)]
#[CoversClass(AbstractObjectCollection::class)]
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
        $receiveResult = \iterable_to_array($receiver->receive(
            new TypedDatasetEntityCollection((new class() extends DatasetEntityContract {
            })::class(), []),
            $this->createMock(ReceiveContextInterface::class),
            $this->createMock(ReceiverStackInterface::class)
        ));
        static::assertCount(0, $receiveResult);
        static::assertTrue($receiver->getSupportedEntityType()->equals(FirstEntity::class()));
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
        $receiveResult = \iterable_to_array($receiver->receive(
            new TypedDatasetEntityCollection((new class() extends DatasetEntityContract {
            })::class(), []),
            $this->createMock(ReceiveContextInterface::class),
            $this->createMock(ReceiverStackInterface::class)
        ));
        static::assertCount(0, $receiveResult);
        static::assertTrue($receiver->getSupportedEntityType()->equals(FirstEntity::class()));
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
        static::assertTrue(FirstEntity::class()->equals($receiver->getSupportedEntityType()));
        static::assertTrue(FirstEntity::class()->equals($decoratingReceiver->getSupportedEntityType()));

        $context = $this->createMock(ReceiveContextInterface::class);
        $entities = new TypedDatasetEntityCollection(FirstEntity::class(), [new FirstEntity()]);

        $singleStack = [$receiver];
        $stackResult = \iterable_to_array((new ReceiverStack($singleStack, $this->createMock(LoggerInterface::class)))->next($entities, $context));
        static::assertCount(\count($singleStack), $stackResult);

        $decoratedStack = [$receiver, $decoratingReceiver];
        $stackResult = \iterable_to_array((new ReceiverStack($decoratedStack, $this->createMock(LoggerInterface::class)))->next($entities, $context));
        static::assertCount(\count($decoratedStack), $stackResult);
    }
}
