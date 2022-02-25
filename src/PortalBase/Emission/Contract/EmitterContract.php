<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\UnsupportedDatasetEntityException;
use Psr\Log\LoggerInterface;

abstract class EmitterContract
{
    private ?string $runDeclaringClass = null;

    private ?string $batchDeclaringClass = null;

    /**
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
     * @return class-string<DatasetEntityContract>
     */
    abstract public function supports(): string;

    protected function run(string $externalId, EmitContextInterface $context): ?DatasetEntityContract
    {
        return null;
    }

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

    protected function extend(DatasetEntityContract $entity, EmitContextInterface $context): DatasetEntityContract
    {
        return $entity;
    }
}
