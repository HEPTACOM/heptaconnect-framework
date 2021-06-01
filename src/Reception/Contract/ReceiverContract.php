<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\UnsupportedDatasetEntityException;

abstract class ReceiverContract
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface>
     */
    public function receive(
        MappedDatasetEntityCollection $mappedDatasetEntities,
        ReceiveContextInterface $context,
        ReceiverStackInterface $stack
    ): iterable {
        yield from $this->receiveCurrent($mappedDatasetEntities, $context);
        yield from $this->receiveNext($stack, $mappedDatasetEntities, $context);
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    abstract public function supports(): string;

    protected function run(
        MappingInterface $mapping,
        DatasetEntityContract $entity,
        ReceiveContextInterface $context
    ): void {
    }

    final protected function isSupported(DatasetEntityContract $entity): bool
    {
        return \is_a($entity, $this->supports(), false);
    }

    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface>
     */
    final protected function receiveNext(
        ReceiverStackInterface $stack,
        MappedDatasetEntityCollection $mappedDatasetEntities,
        ReceiveContextInterface $context
    ): iterable {
        return $stack->next($mappedDatasetEntities, $context);
    }

    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface>
     */
    final protected function receiveCurrent(
        MappedDatasetEntityCollection $mappedDatasetEntities,
        ReceiveContextInterface $context
    ): iterable {
        /** @var MappedDatasetEntityStruct $mappedDatasetEntity */
        foreach ($mappedDatasetEntities as $mappedDatasetEntity) {
            $mapping = $mappedDatasetEntity->getMapping();
            $entity = $mappedDatasetEntity->getDatasetEntity();

            if (!$this->isSupported($entity)) {
                $context->markAsFailed($mapping->getMappingNodeKey(), new UnsupportedDatasetEntityException());

                continue;
            }

            try {
                $this->run($mapping, $entity, $context);
            } catch (\Throwable $throwable) {
                $context->markAsFailed($mapping->getMappingNodeKey(), $throwable);

                continue;
            }

            yield $mapping;
        }
    }

    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface>
     */
    final protected function receiveNextForExtends(
        ReceiverStackInterface $stack,
        MappedDatasetEntityCollection $mappedDatasetEntities,
        ReceiveContextInterface $context
    ): iterable {
        foreach ($this->receiveNext($stack, $mappedDatasetEntities, $context) as $key => $mapping) {
            $mappedEntities = $mappedDatasetEntities->filter(
                static fn (MappedDatasetEntityStruct $o): bool => $o->getMapping()->getDatasetEntityClassName() === $mapping->getDatasetEntityClassName() &&
                    $o->getMapping()->getMappingNodeKey()->equals($mapping->getMappingNodeKey()) &&
                    $o->getMapping()->getPortalNodeKey()->equals($mapping->getPortalNodeKey())
            );

            /** @var MappedDatasetEntityStruct $mappedEntity */
            foreach ($mappedEntities as $mappedEntity) {
                if (!$this->isSupported($mappedEntity->getDatasetEntity())) {
                    break;
                }

                try {
                    $this->run($mapping, $mappedEntity->getDatasetEntity(), $context);
                } catch (\Throwable $throwable) {
                    $context->markAsFailed($mapping->getMappingNodeKey(), $throwable);

                    break;
                }
            }

            yield $key => $mapping;
        }
    }
}
