<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Exploration;

use Heptacom\HeptaConnect\Core\Emission\Contract\EmitterStackProcessorInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\CollectionInterface;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\Contract\PrimaryKeyAwareInterface;
use Heptacom\HeptaConnect\Dataset\Base\EntityType;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Psr\Log\LoggerInterface;

/**
 * @extends AbstractBufferedResultProcessingExplorer<DatasetEntityContract>
 */
final class DirectEmittingExplorer extends AbstractBufferedResultProcessingExplorer
{
    public function __construct(
        EntityType $entityType,
        private DirectEmitter $directEmitter,
        private EmitterStackProcessorInterface $emitterStackProcessor,
        private EmitterStackInterface $emitterStack,
        private EmitContextInterface $emitContext,
        private LoggerInterface $logger,
        int $batchSize
    ) {
        parent::__construct($entityType, $batchSize);
    }

    protected function createBuffer(): CollectionInterface
    {
        return new TypedDatasetEntityCollection($this->getSupportedEntityType());
    }

    protected function processBuffer(CollectionInterface $buffer, ExploreContextInterface $context): void
    {
        $pks = \iterable_to_array($buffer->map(
            static fn (PrimaryKeyAwareInterface $entity): ?string => $entity->getPrimaryKey()
        ));

        $this->logger->debug('DirectEmittingExplorer: Flush a batch of direct emissions', [
            'portalNodeKey' => $context->getPortalNodeKey(),
            'entityType' => $this->getSupportedEntityType(),
            'primaryKeys' => \implode(', ', $pks),
        ]);

        $this->directEmitter->getEntities()->clear();
        $this->directEmitter->getEntities()->push($buffer);
        $this->emitterStackProcessor->processStack($pks, clone $this->emitterStack, $this->emitContext);
        $this->directEmitter->getEntities()->clear();
    }

    protected function pushBuffer(int|string|DatasetEntityContract $value, CollectionInterface $buffer, ExploreContextInterface $context): void
    {
        if ($value instanceof DatasetEntityContract) {
            $this->logger->debug('DirectEmittingExplorer: Entity was explored and job dispatch is prepared', [
                'portalNodeKey' => $context->getPortalNodeKey(),
                'entityType' => $this->getSupportedEntityType(),
                'primaryKey' => $value->getPrimaryKey(),
            ]);

            $buffer->push([$value]);
        }
    }
}
