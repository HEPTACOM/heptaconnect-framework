<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\UnsupportedDatasetEntityException;

abstract class ReceiverContract
{
    /**
     * @return iterable<array-key, DatasetEntityContract>
     */
    public function receive(
        TypedDatasetEntityCollection $entities,
        ReceiveContextInterface $context,
        ReceiverStackInterface $stack
    ): iterable {
        yield from $this->receiveCurrent($entities, $context);
        yield from $this->receiveNext($stack, $entities, $context);
    }

    /**
     * @return class-string<DatasetEntityContract>
     */
    abstract public function supports(): string;

    protected function run(DatasetEntityContract $entity, ReceiveContextInterface $context): void
    {
    }

    protected function batch(TypedDatasetEntityCollection $entities, ReceiveContextInterface $context): void
    {
        /** @var DatasetEntityContract $entity */
        foreach ($entities as $entity) {
            try {
                $this->run($entity, $context);
            } catch (\Throwable $throwable) {
                $context->markAsFailed($entity, $throwable);
            }
        }
    }

    final protected function isSupported(DatasetEntityContract $entity): bool
    {
        return \is_a($entity, $this->supports(), false);
    }

    /**
     * @return iterable<array-key, DatasetEntityContract>
     */
    final protected function receiveNext(
        ReceiverStackInterface $stack,
        TypedDatasetEntityCollection $entities,
        ReceiveContextInterface $context
    ): iterable {
        return $stack->next($entities, $context);
    }

    /**
     * @return iterable<array-key, DatasetEntityContract>
     */
    final protected function receiveCurrent(
        TypedDatasetEntityCollection $entities,
        ReceiveContextInterface $context
    ): iterable {
        if (!\is_a($entities->getType(), $this->supports(), true)) {
            foreach ($entities as $entity) {
                $context->markAsFailed($entity, new UnsupportedDatasetEntityException());
            }

            return;
        }

        $this->batch($entities, $context);

        yield from $entities->getIterator();
    }

    /**
     * @return iterable<array-key, DatasetEntityContract>
     */
    final protected function receiveNextForExtends(
        ReceiverStackInterface $stack,
        TypedDatasetEntityCollection $entities,
        ReceiveContextInterface $context
    ): iterable {
        if (!\is_a($entities->getType(), $this->supports(), true)) {
            foreach ($entities as $entity) {
                $context->markAsFailed($entity, new UnsupportedDatasetEntityException());
            }

            return;
        }

        foreach ($this->receiveNext($stack, $entities, $context) as $entity) {
            try {
                $this->run($entity, $context);
            } catch (\Throwable $throwable) {
                $context->markAsFailed($entity, $throwable);
            }
        }

        yield from $entities->getIterator();
    }
}
