<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Exploration;

use Heptacom\HeptaConnect\Core\Component\LogMessage;
use Heptacom\HeptaConnect\Core\Emission\Contract\EmissionActorInterface;
use Heptacom\HeptaConnect\Core\Emission\Contract\EmitContextFactoryInterface;
use Heptacom\HeptaConnect\Core\Emission\Contract\EmitterStackBuilderFactoryInterface;
use Heptacom\HeptaConnect\Core\Exploration\Contract\ExplorationActorInterface;
use Heptacom\HeptaConnect\Dataset\Base\AttachmentCollection;
use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DependencyCollection;
use Heptacom\HeptaConnect\Dataset\Base\TypedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Emission\Contract\EmitterStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExploreContextInterface;
use Heptacom\HeptaConnect\Portal\Base\Exploration\Contract\ExplorerStackInterface;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappingComponentStruct;
use Heptacom\HeptaConnect\Portal\Base\Publication\Contract\PublisherInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapPayload;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;
use Psr\Log\LoggerInterface;

final class ExplorationActor implements ExplorationActorInterface
{
    public const CHUNK_SIZE_EMISSION = 10;

    public const CHUNK_SIZE_PUBLICATION = 50;

    private LoggerInterface $logger;

    private EmissionActorInterface $emissionActor;

    private EmitContextFactoryInterface $emitContextFactory;

    private PublisherInterface $publisher;

    private EmitterStackBuilderFactoryInterface $emitterStackBuilderFactory;

    private StorageKeyGeneratorContract $storageKeyGenerator;

    private IdentityMapActionInterface $identityMapAction;

    public function __construct(
        LoggerInterface $logger,
        EmissionActorInterface $emissionActor,
        EmitContextFactoryInterface $emitContextFactory,
        PublisherInterface $publisher,
        EmitterStackBuilderFactoryInterface $emitterStackBuilderFactory,
        StorageKeyGeneratorContract $storageKeyGenerator,
        IdentityMapActionInterface $identityMapAction
    ) {
        $this->logger = $logger;
        $this->emissionActor = $emissionActor;
        $this->emitContextFactory = $emitContextFactory;
        $this->publisher = $publisher;
        $this->emitterStackBuilderFactory = $emitterStackBuilderFactory;
        $this->storageKeyGenerator = $storageKeyGenerator;
        $this->identityMapAction = $identityMapAction;
    }

    public function performExploration(
        string $entityType,
        ExplorerStackInterface $stack,
        ExploreContextInterface $context
    ): void {
        $directEmitter = new DirectEmitter($entityType);
        $emissionStack = $this->emitterStackBuilderFactory
            ->createEmitterStackBuilder($context->getPortalNodeKey(), $entityType)
            ->push($directEmitter)
            ->pushDecorators()
            ->build();

        $emitContext = $this->emitContextFactory->createContext($context->getPortalNodeKey(), true);

        $publicationPks = [];
        $emissionPks = [];
        $directEmitter->getEntities()->clear();

        try {
            /** @var DatasetEntityContract|string|int|null $entity */
            foreach ($stack->next($context) as $entity) {
                if ($entity instanceof DatasetEntityContract && ($primaryKey = $entity->getPrimaryKey()) !== null) {
                    $emissionPks[] = $primaryKey;
                    $directEmitter->getEntities()->push([$entity]);

                    $this->logger->debug(\sprintf(
                        'ExplorationActor: Entity was explored and direct emission is prepared. PortalNode: %s; Type: %s; PrimaryKey: %s',
                        $this->storageKeyGenerator->serialize($context->getPortalNodeKey()),
                        $entityType,
                        $primaryKey
                    ));

                    if (\count($emissionPks) >= self::CHUNK_SIZE_EMISSION) {
                        $this->flushDirectEmissions(
                            $emissionStack,
                            $emitContext,
                            $context->getPortalNodeKey(),
                            $entityType,
                            $emissionPks
                        );

                        $emissionPks = [];
                        $directEmitter->getEntities()->clear();
                    }
                } elseif (\is_string($entity) || \is_int($entity)) {
                    $publicationPks[] = (string) $entity;

                    $this->logger->debug(\sprintf(
                        'ExplorationActor: Entity was explored and publication is prepared. PortalNode: %s; Type: %s; PrimaryKey: %s',
                        $this->storageKeyGenerator->serialize($context->getPortalNodeKey()),
                        $entityType,
                        $entity
                    ));

                    if (\count($publicationPks) >= self::CHUNK_SIZE_PUBLICATION) {
                        $this->flushPublications(
                            $context->getPortalNodeKey(),
                            $entityType,
                            $publicationPks
                        );

                        $publicationPks = [];
                    }
                } else {
                    if ($entity instanceof DatasetEntityContract && $entity->getPrimaryKey() === null) {
                        $this->logger->warning(\sprintf(
                            'ExplorationActor: Entity with empty primary key was explored. Type: %s; PortalNode: %s',
                            $entityType,
                            $this->storageKeyGenerator->serialize($context->getPortalNodeKey())
                        ));
                    } else {
                        $this->logger->warning(\sprintf(
                            'ExplorationActor: Empty or invalid primary key was explored. Type: %s; PortalNode: %s',
                            $entityType,
                            $this->storageKeyGenerator->serialize($context->getPortalNodeKey())
                        ));
                    }
                }
            }
        } catch (\Throwable $exception) {
            $this->logger->critical(LogMessage::EXPLORE_NO_THROW(), [
                'type' => $entityType,
                'stack' => $stack,
                'exception' => $exception,
            ]);
        } finally {
            if ($emissionPks !== []) {
                $this->flushDirectEmissions(
                    $emissionStack,
                    $emitContext,
                    $context->getPortalNodeKey(),
                    $entityType,
                    $emissionPks
                );
            }

            if ($publicationPks !== []) {
                $this->flushPublications(
                    $context->getPortalNodeKey(),
                    $entityType,
                    $publicationPks
                );
            }
        }
    }

    /**
     * @param class-string<DatasetEntityContract> $entityType
     *
     * @throws UnsupportedStorageKeyException
     */
    private function flushDirectEmissions(
        EmitterStackInterface $emissionStack,
        EmitContextInterface $emitContext,
        PortalNodeKeyInterface $portalNodeKey,
        string $entityType,
        array $primaryKeys
    ): void {
        $this->logger->debug(\sprintf(
            'ExplorationActor: Flush a batch of direct emissions. PortalNode: %s; Type: %s, PrimaryKeys: %s',
            $this->storageKeyGenerator->serialize($portalNodeKey),
            $entityType,
            \implode(',', $primaryKeys)
        ));

        $mapResult = $this->identityMapAction->map(new IdentityMapPayload($portalNodeKey, $this->factorizeMappableEntities($entityType, $primaryKeys)));

        $this->emissionActor->performEmission(
            $mapResult->getMappedDatasetEntityCollection()->map(static fn (MappedDatasetEntityStruct $me): ?string => $me->getMapping()->getExternalId()),
            clone $emissionStack,
            $emitContext
        );
    }

    /**
     * @param class-string<DatasetEntityContract> $entityType
     *
     * @throws UnsupportedStorageKeyException
     */
    private function flushPublications(
        PortalNodeKeyInterface $portalNodeKey,
        string $entityType,
        array $externalIds
    ): void {
        $this->logger->debug(\sprintf(
            'ExplorationActor: Flush a batch of publications. PortalNode: %s; Type: %s, PrimaryKeys: %s',
            $this->storageKeyGenerator->serialize($portalNodeKey),
            $entityType,
            \implode(',', $externalIds)
        ));

        $entities = $this->factorizeMappableEntities($entityType, $externalIds);

        $mapResult = $this->identityMapAction->map(new IdentityMapPayload($portalNodeKey, $entities));
        $this->publisher->publishBatch(new MappingComponentCollection($mapResult->getMappedDatasetEntityCollection()->map(
            static fn (MappedDatasetEntityStruct $me): MappingComponentStruct => new MappingComponentStruct(
                $me->getMapping()->getPortalNodeKey(),
                $me->getMapping()->getEntityType(),
                $me->getMapping()->getExternalId()
            )
        )));
    }

    /**
     * @param class-string<DatasetEntityContract> $entityType
     * @param string[]                            $primaryKeys
     */
    private function factorizeMappableEntities(string $entityType, array $primaryKeys): TypedDatasetEntityCollection
    {
        $result = new TypedDatasetEntityCollection($entityType);
        $entityFactory = new \ReflectionClass($entityType);

        foreach ($primaryKeys as $primaryKey) {
            $entity = $entityFactory->newInstanceWithoutConstructor();
            \Closure::bind(function (DatasetEntityContract $entity): void {
                $entity->attachments = new AttachmentCollection();
                $entity->dependencies = new DependencyCollection();
            }, null, $entity)($entity);
            $entity->setPrimaryKey($primaryKey);

            $result->push([$entity]);
        }

        return $result;
    }
}
