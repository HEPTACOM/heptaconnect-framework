<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\UnsupportedDatasetEntityException;
use Psr\Log\LoggerInterface;

/**
 * Base class for every emitter implementation with various boilerplate-reducing entrypoints for rapid development.
 * When used as source for entities, you most likely implement @see supports, batch, run
 * When used as decorator to enrich data from previous emissions, you should implement @see supports, extend
 */
abstract class EmitterContract
{
    private ?string $runDeclaringClass = null;

    private ?string $batchDeclaringClass = null;

    /**
     * First entrypoint to handle an emission in this flow component.
     * It allows direct stack handling manipulation. @see EmitterStackInterface
     * You most likely want to implement @see run, batch instead.
     *
     * @param string[] $externalIds
     *
     * @return iterable<array-key, DatasetEntityContract>
     */
    public function emit(iterable $externalIds, EmitContextInterface $context, EmitterStackInterface $stack): iterable
    {
        /** @var string[] $rewindableExternalIds */
        $rewindableExternalIds = \iterable_to_array($externalIds);

        if (!$context->isDirectEmission()) {
            yield from $this->emitCurrent($rewindableExternalIds, $context);
        }

        yield from $this->emitNext($stack, $rewindableExternalIds, $context);
    }

    /**
     * Must return the supported entity type.
     *
     * @return class-string<DatasetEntityContract>
     */
    abstract public function supports(): string;

    /**
     * The entrypoint for handling an emission with the least need of additional programming.
     * This is executed when this emitter on the stack is expected to act.
     * It can be skipped when @see emit, batch is implemented accordingly.
     * Returns an entity, when the data could be read, otherwise null.
     */
    protected function run(string $externalId, EmitContextInterface $context): ?DatasetEntityContract
    {
        return null;
    }

    /**
     * Returns true, when the given entity is of the supported entity type.
     */
    final protected function isSupported(?DatasetEntityContract $entity): bool
    {
        if ($entity === null) {
            return false;
        }

        if (!\is_a($entity, $this->supports(), false)) {
            return false;
        }

        return true;
    }

    /**
     * Pre-implemented stack handling for processing the next emitter in the stack.
     * Expected to only be called by @see emit
     * It allows extending previous emitted entities with @see extend
     *
     * @param string[] $externalIds
     *
     * @return iterable<array-key, DatasetEntityContract>
     */
    final protected function emitNext(
        EmitterStackInterface $stack,
        iterable $externalIds,
        EmitContextInterface $context
    ): iterable {
        foreach ($stack->next($externalIds, $context) as $key => $entity) {
            $primaryKey = $entity->getPrimaryKey();

            if ($primaryKey === null) {
                /** @var LoggerInterface|null $logger */
                $logger = $context->getContainer()->get(LoggerInterface::class);

                if ($logger instanceof LoggerInterface) {
                    $logger->error(\sprintf(
                        'Emitter "%s" returned an entity with empty primary key. Skipping.',
                        static::class
                    ));
                }

                continue;
            }

            try {
                $entity = $this->extend($entity, $context);

                if (!$this->isSupported($entity)) {
                    $context->markAsFailed($primaryKey, $this->supports(), new UnsupportedDatasetEntityException(
                        \sprintf(
                            'Emitter "%s" returned object of unsupported type. Expected "%s" but got "%s".',
                            static::class,
                            $this->supports(),
                            \get_class($entity)
                        )
                    ));

                    continue;
                }
            } catch (\Throwable $exception) {
                $context->markAsFailed($primaryKey, $this->supports(), $exception);

                continue;
            }

            if ($entity instanceof DatasetEntityContract) {
                yield $key => $entity;
            }
        }
    }

    /**
     * Pre-implemented stack handling for processing this emitter in the stack.
     * Expected to only be called by @see emit
     *
     * @param string[] $externalIds
     *
     * @return iterable<array-key, DatasetEntityContract>
     */
    final protected function emitCurrent(iterable $externalIds, EmitContextInterface $context): iterable
    {
        /** @var LoggerInterface|null $logger */
        $logger = $context->getContainer()->get(LoggerInterface::class);

        /** @var iterable<string> $externalIds */
        $externalIds = \iterable_filter($externalIds, function (?string $externalId) use ($logger): bool {
            if (!\is_string($externalId)) {
                if ($logger instanceof LoggerInterface) {
                    $logger->error(\sprintf(
                        'Empty primary key was passed to emitter "%s". Skipping.',
                        static::class
                    ));
                }

                return false;
            }

            return true;
        });

        foreach ($this->batch($externalIds, $context) as $entity) {
            if (!$this->isSupported($entity)) {
                $this->runDeclaringClass ??= (new \ReflectionMethod($this, 'run'))->getDeclaringClass()->getName();
                $this->batchDeclaringClass ??= (new \ReflectionMethod($this, 'batch'))->getDeclaringClass()->getName();

                if ($this->runDeclaringClass === self::class && $this->batchDeclaringClass === self::class) {
                    continue;
                }

                if ($logger instanceof LoggerInterface) {
                    $logger->error(\sprintf(
                        'Emitter "%s" returned object of unsupported type. Expected "%s" but got "%s".',
                        static::class,
                        $this->supports(),
                        $entity === null ? 'null' : \get_class($entity)
                    ));
                }
            } elseif ($entity instanceof DatasetEntityContract) {
                yield $entity;
            }
        }
    }

    /**
     * The best entrypoint for handling an emission performant without to be expected to implement stack handling.
     * This is executed when this emitter on the stack is expected to act.
     * It can be skipped when @see emit is implemented accordingly.
     * By default it executes @see run to process every entity in the batch and mark them as failed in case of an exception.
     *
     * @param iterable<string> $externalIds
     *
     * @return iterable<DatasetEntityContract|null>
     */
    protected function batch(iterable $externalIds, EmitContextInterface $context): iterable
    {
        foreach ($externalIds as $externalId) {
            try {
                yield $this->run($externalId, $context);
            } catch (\Throwable $exception) {
                $context->markAsFailed($externalId, $this->supports(), $exception);
            }
        }
    }

    /**
     * The entrypoint for adding additional or changing existing data on an entity.
     * It is only called from @see emitNext to extend previously emitted entities on the stack.
     */
    protected function extend(DatasetEntityContract $entity, EmitContextInterface $context): DatasetEntityContract
    {
        return $entity;
    }
}
