<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\MissingExternalIdException;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\UnsupportedDatasetEntityException;

abstract class EmitterContract
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct>
     */
    public function emit(MappingCollection $mappings, EmitContextInterface $context, EmitterStackInterface $stack): iterable
    {
        yield from $this->emitCurrent($mappings, $context);
        yield from $this->emitNext($stack, $mappings, $context);
    }

    /**
     * @return class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>
     */
    abstract public function supports(): string;

    protected function run(
        MappingInterface $mapping,
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
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct>
     */
    final protected function emitNext(
        EmitterStackInterface $stack,
        MappingCollection $mappings,
        EmitContextInterface $context
    ): iterable {
        foreach ($stack->next($mappings, $context) as $key => $mappedDatasetEntity) {
            $mapping = $mappedDatasetEntity->getMapping();

            if ($mapping->getExternalId() === null) {
                throw new MissingExternalIdException();
            }

            $entity = $mappedDatasetEntity->getDatasetEntity();

            try {
                $entity = $this->extend($mapping, $entity, $context);

                if (!$this->isSupported($entity)) {
                    $context->markAsFailed($mapping, new UnsupportedDatasetEntityException(
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
                $context->markAsFailed($mapping, $exception);

                continue;
            }

            if ($entity instanceof DatasetEntityContract) {
                yield $key => new MappedDatasetEntityStruct($mapping, $entity);
            }
        }
    }

    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct>
     */
    final protected function emitCurrent(MappingCollection $mappings, EmitContextInterface $context): iterable
    {
        /** @var MappingInterface $mapping */
        foreach ($mappings as $mapping) {
            if ($mapping->getExternalId() === null) {
                throw new MissingExternalIdException();
            }

            try {
                $entity = $this->run($mapping, $context);

                if (!$this->isSupported($entity)) {
                    throw new UnsupportedDatasetEntityException(
                        \sprintf(
                            'Emitter "%s" returned object of unsupported type. Expected "%s" but got "%s".',
                            static::class,
                            $this->supports(),
                            $entity === null ? 'null' : \get_class($entity)
                        )
                    );
                }
            } catch (\Throwable $exception) {
                $context->markAsFailed($mapping, $exception);

                continue;
            }

            if ($entity instanceof DatasetEntityContract) {
                yield new MappedDatasetEntityStruct($mapping, $entity);
            }
        }
    }

    protected function extend(
        MappingInterface $mapping,
        DatasetEntityContract $entity,
        EmitContextInterface $context
    ): DatasetEntityContract {
        return $entity;
    }
}
