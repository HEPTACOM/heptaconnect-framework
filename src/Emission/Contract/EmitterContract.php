<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\UnsupportedDatasetEntityException;

abstract class EmitterContract
{
    /**
     * @param string[] $externalIds
     *
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    public function emit(iterable $externalIds, EmitContextInterface $context, EmitterStackInterface $stack): iterable
    {
        $externalIds = \iterable_to_array($externalIds);

        yield from $this->emitCurrent($externalIds, $context);
        yield from $this->emitNext($stack, $externalIds, $context);
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    abstract public function supports(): string;

    protected function run(
        string $externalId,
        EmitContextInterface $context
    ): ?DatasetEntityContract {
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
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    final protected function emitNext(
        EmitterStackInterface $stack,
        iterable $externalIds,
        EmitContextInterface $context
    ): iterable {
        foreach ($stack->next($externalIds, $context) as $key => $entity) {
            $primaryKey = $entity->getPrimaryKey();

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
     * @return iterable<array-key, \Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    final protected function emitCurrent(iterable $externalIds, EmitContextInterface $context): iterable
    {
        foreach ($externalIds as $externalId) {
            try {
                $entity = $this->run($externalId, $context);

                if (!$this->isSupported($entity)) {
                    throw new UnsupportedDatasetEntityException(\sprintf('Emitter "%s" returned object of unsupported type. Expected "%s" but got "%s".', static::class, $this->supports(), $entity === null ? 'null' : \get_class($entity)));
                }
            } catch (\Throwable $exception) {
                $context->markAsFailed($externalId, $this->supports(), $exception);

                continue;
            }

            if ($entity instanceof DatasetEntityContract) {
                yield $entity;
            }
        }
    }

    protected function extend(
        DatasetEntityContract $entity,
        EmitContextInterface $context
    ): DatasetEntityContract {
        return $entity;
    }
}
