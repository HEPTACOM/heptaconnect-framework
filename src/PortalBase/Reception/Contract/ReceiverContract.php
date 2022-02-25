<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\UnsupportedDatasetEntityException;

/**
 * Base class for every receiver implementation with various boilerplate-reducing entrypoints for fast development.
 */
abstract class ReceiverContract
{
    /**
     * First entrypoint to handle a reception in this flow component.
     * It allows direct stack handling manipulation. @see ReceiverStackInterface
     * You most likely want to implement @see run instead.
     *
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
     * Must return the supported entity type.
     *
     * @return class-string<DatasetEntityContract>
     */
    abstract public function supports(): string;

    /**
     * The entrypoint for handling a reception with the least need of additional programming.
     * This is executed when this receiver on the stack is expected to act.
     * It can be skipped when @see receive is implemented accordingly.
     * When the targeted API supports access in batches it is likely you need to implement @see batch instead or in addition.
     */
    protected function run(DatasetEntityContract $entity, ReceiveContextInterface $context): void
    {
    }

    /**
     * The best entrypoint for handling a reception performant without to be expected to implement stack handling.
     * This is executed when this receiver on the stack is expected to act.
     * It can be skipped when @see receive is implemented accordingly.
     * By default it executes @see run to process every entity in the batch and mark them as failed in case of an exception.
     */
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

    /**
     * Returns true, when the given entity is of the supported entity type.
     */
    final protected function isSupported(DatasetEntityContract $entity): bool
    {
        return \is_a($entity, $this->supports(), false);
    }

    /**
     * Pre-implemented stack handling for processing the next receiver in the stack.
     * Expected to be used when implementing @see receive by yourself.
     *
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
     * Pre-implemented stack handling for processing this receiver in the stack.
     * Expected to be used when implementing @see receive by yourself.
     *
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
     * Pre-implemented stack handling for processing the next receiver in the stack and pass its results into this receiver @see run
     * Expected to be used when implementing @see receive by yourself.
     *
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
