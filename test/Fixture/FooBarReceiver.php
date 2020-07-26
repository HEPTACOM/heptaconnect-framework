<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Test\Fixture;

use Heptacom\HeptaConnect\Portal\Base\Contract\ReceiverInterface;
use Heptacom\HeptaConnect\Portal\Base\Contract\ReceiverStackInterface;
use Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Reception\Contract\ReceiveContextInterface;

class FooBarReceiver implements ReceiverInterface
{
    public function receive(
        MappedDatasetEntityCollection $mappedDatasetEntities,
        ReceiveContextInterface $context,
        ReceiverStackInterface $stack
    ): iterable {
        /** @var MappedDatasetEntityStruct $mappedDatasetEntity */
        foreach ($mappedDatasetEntities as $mappedDatasetEntity) {
            $mapping = $mappedDatasetEntity->getMapping();
            $mapping->setExternalId('');

            yield $mapping;
        }

        yield from $stack->next($mappedDatasetEntities, $context);
    }

    public function supports(): array
    {
        return [
            FooBarEntity::class,
        ];
    }
}
