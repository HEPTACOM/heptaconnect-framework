<?php
declare(strict_types=1);

namespace Heptacom\HeptaConnect\Core\Mapping;

use Heptacom\HeptaConnect\Core\Mapping\Contract\MappingServiceInterface;
use Heptacom\HeptaConnect\Core\Mapping\Exception\MappingNodeAreUnmergableException;
use Heptacom\HeptaConnect\Portal\Base\Mapping\Contract\MappingInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\MappingNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Repository\MappingExceptionRepositoryContract;
use Heptacom\HeptaConnect\Storage\Base\Contract\Repository\MappingNodeRepositoryContract;
use Heptacom\HeptaConnect\Storage\Base\Contract\Repository\MappingRepositoryContract;
use Heptacom\HeptaConnect\Storage\Base\Contract\StorageKeyGeneratorContract;
use Heptacom\HeptaConnect\Storage\Base\Exception\NotFoundException;
use Heptacom\HeptaConnect\Storage\Base\Exception\UnsupportedStorageKeyException;
use Heptacom\HeptaConnect\Storage\Base\MappingCollection;
use Psr\Log\LoggerInterface;

class MappingService implements MappingServiceInterface
{
    private MappingRepositoryContract $mappingRepository;

    private MappingExceptionRepositoryContract $mappingExceptionRepository;

    private MappingNodeRepositoryContract $mappingNodeRepository;

    private StorageKeyGeneratorContract $storageKeyGenerator;

    private LoggerInterface $logger;

    public function __construct(
        MappingRepositoryContract $mappingRepository,
        MappingExceptionRepositoryContract $mappingExceptionRepository,
        MappingNodeRepositoryContract $mappingNodeRepository,
        StorageKeyGeneratorContract $storageKeyGenerator,
        LoggerInterface $logger
    ) {
        $this->mappingRepository = $mappingRepository;
        $this->mappingExceptionRepository = $mappingExceptionRepository;
        $this->mappingNodeRepository = $mappingNodeRepository;
        $this->storageKeyGenerator = $storageKeyGenerator;
        $this->logger = $logger;
    }

    public function addException(
        PortalNodeKeyInterface $portalNodeKey,
        MappingNodeKeyInterface $mappingNodeKey,
        \Throwable $exception
    ): void {
        try {
            $this->mappingExceptionRepository->create($portalNodeKey, $mappingNodeKey, $exception);
        } catch (\Throwable $throwable) {
            $this->logger->error('MAPPING_EXCEPTION', [
                'exception' => $exception,
                'mappingNodeKey' => $this->storageKeyGenerator->serialize($mappingNodeKey),
                'portalNodeKey' => $this->storageKeyGenerator->serialize($portalNodeKey),
                'outerException' => $throwable,
            ]);
        }
    }

    public function get(
        string $entityType,
        PortalNodeKeyInterface $portalNodeKey,
        string $externalId
    ): MappingInterface {
        $mappingNodeKey = $this->getMappingNodeKey($entityType, $portalNodeKey, $externalId);
        $mappingExists = $mappingNodeKey instanceof MappingNodeKeyInterface;

        if (!$mappingExists) {
            /* @phpstan-ignore-next-line */
            $mappingNodeKey = $this->mappingNodeRepository->create($entityType, $portalNodeKey);
        }

        $mapping = (new MappingStruct($portalNodeKey, new MappingNodeStruct($mappingNodeKey, $entityType)))
            ->setExternalId($externalId);

        if (!$mappingExists) {
            $this->mappingRepository->create(
                $mapping->getPortalNodeKey(),
                $mapping->getMappingNodeKey(),
                $mapping->getExternalId()
            );
        }

        return $mapping;
    }

    public function getListByExternalIds(
        string $entityType,
        PortalNodeKeyInterface $portalNodeKey,
        array $externalIds
    ): iterable {
        $mappingNodeKeys = $this->mappingNodeRepository->listByTypeAndPortalNodeAndExternalIds(
            $entityType,
            $portalNodeKey,
            $externalIds
        );
        $newExternalIds = $externalIds;

        foreach ($mappingNodeKeys as $mappedExternalId => $mappingNodeKey) {
            if (($match = \array_search($mappedExternalId, $newExternalIds, true)) !== false) {
                unset($newExternalIds[$match]);
                yield $mappedExternalId => (new MappingStruct(
                    $portalNodeKey,
                    new MappingNodeStruct($mappingNodeKey, $entityType))
                )->setExternalId($mappedExternalId);
            }
        }

        $newMappingNodeKeys = $this->mappingNodeRepository->createList($entityType, $portalNodeKey, \count($newExternalIds));
        $newMappingNodeKeysIterator = $newMappingNodeKeys->getIterator();
        $createPayload = new MappingCollection();

        foreach ($newExternalIds as $newExternalId) {
            if (!$newMappingNodeKeysIterator->valid()) {
                break;
            }

            $mappingNodeKey = $newMappingNodeKeysIterator->current();

            if (!$mappingNodeKey instanceof MappingNodeKeyInterface) {
                continue;
            }

            $createPayload->push([
                (new MappingStruct($portalNodeKey, new MappingNodeStruct($mappingNodeKey, $entityType)))
                    ->setExternalId($newExternalId),
            ]);

            $newMappingNodeKeysIterator->next();
        }

        $this->mappingRepository->createList($createPayload);

        /** @var MappingStruct $mapping */
        foreach ($createPayload as $mapping) {
            yield $mapping->getExternalId() => $mapping;
        }
    }

    public function save(MappingInterface $mapping): void
    {
        $mappingKeys = $this->mappingRepository->listByNodes(
            $mapping->getMappingNodeKey(),
            $mapping->getPortalNodeKey()
        );

        foreach ($mappingKeys as $mappingKey) {
            $this->mappingRepository->updateExternalId($mappingKey, $mapping->getExternalId());

            return;
        }

        $this->mappingRepository->create(
            $mapping->getPortalNodeKey(),
            $mapping->getMappingNodeKey(),
            $mapping->getExternalId()
        );
    }

    public function reflect(MappingInterface $mapping, PortalNodeKeyInterface $portalNodeKey): MappingInterface
    {
        $this->createIfNeeded($mapping);
        $mappingKeys = $this->mappingRepository->listByNodes($mapping->getMappingNodeKey(), $portalNodeKey);

        foreach ($mappingKeys as $mappingKey) {
            return $this->mappingRepository->read($mappingKey);
        }

        $mappingNode = new MappingNodeStruct($mapping->getMappingNodeKey(), $mapping->getEntityType());

        return new MappingStruct($portalNodeKey, $mappingNode);
    }

    public function merge(MappingNodeKeyInterface $mergeFrom, MappingNodeKeyInterface $mergeInto): void
    {
        try {
            $nodeFrom = $this->mappingNodeRepository->read($mergeFrom);
            $nodeInto = $this->mappingNodeRepository->read($mergeInto);
            if ($nodeFrom->getEntityType() !== $nodeInto->getEntityType()) {
                throw new MappingNodeAreUnmergableException($mergeFrom, $mergeInto);
            }

            $intoPortalExistences = [];

            foreach ($this->mappingRepository->listByMappingNode($mergeInto) as $mappingKey) {
                $mapping = $this->mappingRepository->read($mappingKey);
                $portalNode = $this->storageKeyGenerator->serialize($mapping->getPortalNodeKey());

                $intoPortalExistences[$portalNode] = $mapping->getExternalId();
            }

            /** @var MappingInterface[] $mappingsToCreate */
            $mappingsToCreate = [];
            /** @var MappingKeyInterface[] $mappingsToDelete */
            $mappingsToDelete = [];

            foreach ($this->mappingRepository->listByMappingNode($mergeFrom) as $mappingKey) {
                $mapping = $this->mappingRepository->read($mappingKey);
                $portalNode = $this->storageKeyGenerator->serialize($mapping->getPortalNodeKey());

                if (\array_key_exists($portalNode, $intoPortalExistences) && $intoPortalExistences[$portalNode] !== null) {
                    if ($intoPortalExistences[$portalNode] !== $mapping->getExternalId()) {
                        throw new MappingNodeAreUnmergableException($mergeFrom, $mergeInto);
                    }
                } else {
                    $mappingsToCreate[] = $mapping;
                }

                $mappingsToDelete[] = $mappingKey;
            }

            \array_walk($mappingsToDelete, [$this->mappingRepository, 'delete']);

            foreach ($mappingsToCreate as $mapping) {
                $this->mappingRepository->create($mapping->getPortalNodeKey(), $mergeInto, $mapping->getExternalId());
            }

            $this->mappingNodeRepository->delete($mergeFrom);
        } catch (NotFoundException $e) {
            throw new MappingNodeAreUnmergableException($mergeFrom, $mergeInto, $e);
        } catch (UnsupportedStorageKeyException $e) {
            throw new MappingNodeAreUnmergableException($mergeFrom, $mergeInto, $e);
        }
    }

    private function createIfNeeded(MappingInterface $mapping): void
    {
        $mappingKeys = $this->mappingRepository->listByNodes(
            $mapping->getMappingNodeKey(),
            $mapping->getPortalNodeKey()
        );

        foreach ($mappingKeys as $_) {
            return;
        }

        $this->mappingRepository->create(
            $mapping->getPortalNodeKey(),
            $mapping->getMappingNodeKey(),
            $mapping->getExternalId()
        );
    }

    private function getMappingNodeKey(
        string $entityType,
        PortalNodeKeyInterface $portalNodeKey,
        string $externalId
    ): ?MappingNodeKeyInterface {
        /* @phpstan-ignore-next-line */
        $ids = $this->mappingNodeRepository->listByTypeAndPortalNodeAndExternalId(
            $entityType,
            $portalNodeKey,
            $externalId
        );

        foreach ($ids as $id) {
            return $id;
        }

        return null;
    }
}