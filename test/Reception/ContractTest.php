<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Test\Reception;

use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverContract;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiverStackInterface;
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

            public function supports(): array
            {
                return [];
            }
        };
        static::assertCount(0, $receiver->receive(
            new MappedDatasetEntityCollection(),
            $this->createMock(ReceiveContextInterface::class),
            $this->createMock(ReceiverStackInterface::class)
        ));
        static::assertEmpty($receiver->supports());
    }

    public function testAttachmentReadingReceiverContract(): void
    {
        $receiver = new class() extends ReceiverContract {
            public function supports(): array
            {
                return [FirstEntity::class];
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

            public function supports(): array
            {
                return [FirstEntity::class];
            }
        };
        static::assertContains(FirstEntity::class, $receiver->supports());
        static::assertContains(FirstEntity::class, $decoratingReceiver->supports());

        $context = $this->createMock(ReceiveContextInterface::class);
        $stack = $this->createMock(ReceiverStackInterface::class);
        $mapping = $this->createMock(MappingInterface::class);
        $mappedEntities = new MappedDatasetEntityCollection([new MappedDatasetEntityStruct($mapping, new FirstEntity())]);

        $mapping->method('getExternalId')->willReturn('');
        $stack->method('next')->willReturn($receiver->receive($mappedEntities, $context, $stack));

        static::assertCount(1, $receiver->receive(
            $mappedEntities,
            $context,
            $this->createMock(ReceiverStackInterface::class)
        ));
        static::assertCount(1, $decoratingReceiver->receive($mappedEntities, $context, $stack));
    }
}
