<?php declare(strict_types=1);

namespace Heptacom\HeptaConnect\Portal\Base\Reception\Contract;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Portal\Contract\PortalContract;
use Heptacom\HeptaConnect\Portal\Base\Portal\Exception\MissingExternalIdException;
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
        /** @var MappedDatasetEntityStruct $mappedDatasetEntity */
        foreach ($mappedDatasetEntities as $mappedDatasetEntity) {
            $mapping = $mappedDatasetEntity->getMapping();
            $portal = $context->getPortal($mapping);
            $entity = $mappedDatasetEntity->getDatasetEntity();

            if (!$this->isSupported($entity)) {
                $context->markAsFailed($mapping, new UnsupportedDatasetEntityException());

                continue;
            }

            try {
                $this->run($portal, $mapping, $entity, $context);
            } catch (\Throwable $throwable) {
                $context->markAsFailed($mapping, $throwable);

                continue;
            }

            if ($mapping->getExternalId() === null) {
                $context->markAsFailed($mapping, new MissingExternalIdException());

                continue;
            }

            yield $mapping;
        }

        return $stack->next($mappedDatasetEntities, $context);
    }

    /**
     * @return array<array-key, class-string<\Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityInterface>>
     */
    abstract public function supports(): array;

    protected function run(
        PortalContract $portal,
        MappingInterface $mapping,
        DatasetEntityInterface $entity,
        ReceiveContextInterface $context
    ): void {
    }

    private function isSupported(DatasetEntityInterface $entity): bool
    {
        foreach ($this->supports() as $dataType) {
            if (\is_a($entity, $dataType, false)) {
                return true;
            }
        }

        return false;
    }
}
