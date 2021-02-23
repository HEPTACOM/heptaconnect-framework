<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Emission\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingCollection;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\MissingExternalIdException;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\UnsupportedDatasetEntityException;

abstract class EmitterContract
{
    /**
     * @return iterable<array-key, \Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct>
     */
    public function emit(MappingCollection $mappings, EmitContextInterface $context, EmitterStackInterface $stack): iterable
    {
        /** @var MappingInterface $mapping */
        foreach ($mappings as $mapping) {
            if ($mapping->getExternalId() === null) {
                throw new MissingExternalIdException();
            }

            $portal = $context->getPortal($mapping);

            try {
                $entity = $this->run($portal, $mapping, $context);

                if (!$this->isSupported($entity)) {
                    throw new UnsupportedDatasetEntityException();
                }
            } catch (\Throwable $exception) {
                $context->markAsFailed($mapping, $exception);

                continue;
            }

            if ($entity instanceof DatasetEntityContract) {
                yield new MappedDatasetEntityStruct($mapping, $entity);
            }
        }

        return $stack->next($mappings, $context);
    }

    /**
     * @return array<array-key, class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract>>
     */
    abstract public function supports(): array;

    protected function run(
        PortalContract $portal,
        MappingInterface $mapping,
        EmitContextInterface $context
    ): ?DatasetEntityContract {
        return null;
    }

    private function isSupported(?DatasetEntityContract $entity): bool
    {
        if ($entity === null) {
            return false;
        }

        foreach ($this->supports() as $dataType) {
            if (\is_a($entity, $dataType, false)) {
                return true;
            }
        }

        return false;
    }
}
