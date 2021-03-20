<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Reception;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\ReceiverStack;
use Heptacom\HeptaConnect\Portal\Base\Test\Fixture\FirstEntity;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract
 */
class ContractTest extends TestCase
{
    public function testExtendingReceiverContract(): void
    {
        $receiver = new class() extends ReceiverContract {
            public function receive(
                MappedDatasetEntityCollection $mappedDatasetEntities,
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
        static::assertCount(0, $receiver->receive(
            new MappedDatasetEntityCollection(),
            $this->createMock(ReceiveContextInterface::class),
            $this->createMock(ReceiverStackInterface::class)
        ));
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
                MappedDatasetEntityCollection $mappedDatasetEntities,
                ReceiveContextInterface $context,
                ReceiverStackInterface $stack
            ): iterable {
                return $this->receiveNextForExtends($stack, $mappedDatasetEntities, $context);
            }

            public function supports(): string
            {
                return FirstEntity::class;
            }
        };
        static::assertSame(FirstEntity::class, $receiver->supports());
        static::assertSame(FirstEntity::class, $decoratingReceiver->supports());

        $context = $this->createMock(ReceiveContextInterface::class);
        $mapping = $this->createMock(MappingInterface::class);
        $mappedEntities = new MappedDatasetEntityCollection([new MappedDatasetEntityStruct($mapping, new FirstEntity())]);

        $mapping->method('getExternalId')->willReturn('');

        static::assertCount(1, (new ReceiverStack([$receiver]))->next($mappedEntities, $context));
        static::assertCount(1, (new ReceiverStack([$receiver, $decoratingReceiver]))->next($mappedEntities, $context));
    }
}
