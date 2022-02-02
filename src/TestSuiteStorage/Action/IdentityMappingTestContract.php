<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Action;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Portal\Base\Support\Contract\DeepObjectIteratorContract;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Mapping;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist\IdentityPersistCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist\IdentityPersistPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist\IdentityPersistPayloadCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Reflect\IdentityReflectPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\Storage\Base\PrimaryKeySharingMappingStruct;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityB;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Dataset\EntityC;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalA\PortalA;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalB\PortalB;
use Heptacom\HeptaConnect\TestSuite\Storage\Fixture\Portal\PortalC\PortalC;
use Heptacom\HeptaConnect\TestSuite\Storage\TestCase;

abstract class IdentityMappingTestContract extends TestCase
{
    private ?PortalNodeKeyInterface $portalA = null;

    private ?PortalNodeKeyInterface $portalB = null;

    private ?PortalNodeKeyInterface $portalC = null;

    /**
     * @param class-string<DatasetEntityContract> $entityClass
     * @dataProvider provideEntityClasses
     */
    public function testIdentityMap(string $entityClass): void
    {
        $facade = $this->createStorageFacade();
        $identityMap = $facade->getIdentityMapAction();

        /** @var DatasetEntityContract $entityA */
        $entityA = new $entityClass();
        $entityA->setPrimaryKey('57945df7-b8c8-4cca-a92e-53b71e8753ad');
        /** @var DatasetEntityContract $entityB */
        $entityB = new $entityClass();
        $entityB->setPrimaryKey($entityA->getPrimaryKey());

        $identityMapResult = $identityMap->map(new IdentityMapPayload($this->portalA, new DatasetEntityCollection([
            $entityA,
            $entityB,
        ])));

        static::assertSame(2, $identityMapResult->getMappedDatasetEntityCollection()->count());

        /** @var MappedDatasetEntityStruct|null $firstEntity */
        $firstEntity = $identityMapResult->getMappedDatasetEntityCollection()->first();
        /** @var MappedDatasetEntityStruct|null $secondEntity */
        $secondEntity = $identityMapResult->getMappedDatasetEntityCollection()->last();

        static::assertNotNull($firstEntity);
        static::assertInstanceOf(MappedDatasetEntityStruct::class, $firstEntity);
        static::assertEquals($entityA->getPrimaryKey(), $firstEntity->getMapping()->getExternalId());

        static::assertNotNull($secondEntity);
        static::assertInstanceOf(MappedDatasetEntityStruct::class, $secondEntity);
        static::assertEquals($entityA->getPrimaryKey(), $secondEntity->getMapping()->getExternalId());

        static::assertNotSame($firstEntity, $secondEntity);
    }

    /**
     * @param class-string<DatasetEntityContract> $entityClass
     * @dataProvider provideEntityClasses
     */
    public function testReflectToDifferentPortalNode(string $entityClass): void
    {
        $facade = $this->createStorageFacade();
        $identityMap = $facade->getIdentityMapAction();
        $identityReflect = $facade->getIdentityReflectAction();
        $identityPersist = $facade->getIdentityPersistAction();

        /** @var DatasetEntityContract $datasetEntity */
        $datasetEntity = new $entityClass();
        $datasetEntity->setPrimaryKey('e9011418-5535-4180-93e9-94b44cc3e28d');

        $tracked = new DatasetEntityCollection((new DeepObjectIteratorContract())->iterate($datasetEntity));
        $identityMapResult = $identityMap->map(new IdentityMapPayload($this->portalA, $tracked));
        $identityPersistPayload = new IdentityPersistPayload($this->portalB, new IdentityPersistPayloadCollection());
        $mappedEntities = new MappedDatasetEntityCollection();
        $mappingPairs = [];

        /** @var MappedDatasetEntityStruct $mappedEntity */
        foreach ($identityMapResult->getMappedDatasetEntityCollection() as $mappedEntity) {
            /** @var DatasetEntityContract $entity */
            $entity = new $entityClass();
            $sourceId = $mappedEntity->getMapping()->getExternalId();
            $targetId = $sourceId . '-other-side';
            $mappingNodeKey = $mappedEntity->getMapping()->getMappingNodeKey();
            $mappingPairs['object_hash'][\spl_object_hash($entity)] = $targetId;
            $mappingPairs['reflection_mapping'][$sourceId] = $targetId;

            $entity->setPrimaryKey($sourceId);
            $mappedEntities->push([
                new MappedDatasetEntityStruct(
                    new Mapping($sourceId, $this->portalA, $mappingNodeKey, $entityClass),
                    $entity
                )
            ]);

            $identityPersistPayload->getMappingPersistPayloads()->push([
                new IdentityPersistCreatePayload($mappingNodeKey, $targetId)
            ]);
        }

        $identityPersist->persist($identityPersistPayload);
        $identityReflect->reflect(new IdentityReflectPayload($this->portalB, $mappedEntities));

        /** @var MappedDatasetEntityStruct $mappedEntity */
        foreach ($mappedEntities as $mappedEntity) {
            $entity = $mappedEntity->getDatasetEntity();
            /** @var PrimaryKeySharingMappingStruct $reflectionMapping */
            $reflectionMapping = $entity->getAttachment(PrimaryKeySharingMappingStruct::class);

            static::assertSame($mappingPairs['object_hash'][\spl_object_hash($entity)], $entity->getPrimaryKey());
            static::assertSame($mappingPairs['reflection_mapping'][$reflectionMapping->getExternalId()], $entity->getPrimaryKey());
        }
    }

    /**
     * @param class-string<DatasetEntityContract> $entityClass
     * @dataProvider provideEntityClasses
     */
    public function testReflectDatasetEntityTwiceToDifferentPortalNode(string $entityClass): void
    {
        $facade = $this->createStorageFacade();
        $identityMap = $facade->getIdentityMapAction();
        $identityReflect = $facade->getIdentityReflectAction();
        $identityPersist = $facade->getIdentityPersistAction();

        $datasetEntity = new $entityClass();
        $datasetEntity->setPrimaryKey($datasetEntity->getPrimaryKey() ?? 'c1870a69-e409-4dbc-be22-d7ac1071cb0c');
        $datasetEntity->attach($datasetEntity);
        $tracked = new DatasetEntityCollection((new DeepObjectIteratorContract())->iterate($datasetEntity));
        $identityMapResult = $identityMap->map(new IdentityMapPayload($this->portalA, $tracked));
        $identityPersistPayload = new IdentityPersistPayload($this->portalB, new IdentityPersistPayloadCollection());
        $mappedEntities = new MappedDatasetEntityCollection();

        /** @var MappedDatasetEntityStruct $mappedEntity */
        foreach ($identityMapResult->getMappedDatasetEntityCollection() as $mappedEntity) {
            /** @var DatasetEntityContract $entity */
            $entity = new $entityClass();
            $sourceId = $mappedEntity->getMapping()->getExternalId();
            $targetId = $sourceId . '-other-side';
            $mappingNodeKey = $mappedEntity->getMapping()->getMappingNodeKey();

            $entity->setPrimaryKey($sourceId);
            $mappedEntities->push([
                new MappedDatasetEntityStruct(
                    new Mapping($sourceId, $this->portalA, $mappingNodeKey, $entityClass),
                    $entity
                )
            ]);

            $identityPersistPayload->getMappingPersistPayloads()->push([
                new IdentityPersistCreatePayload($mappingNodeKey, $targetId)
            ]);
        }

        $identityPersist->persist($identityPersistPayload);
        $identityReflect->reflect(new IdentityReflectPayload($this->portalB, $mappedEntities));

        /** @var MappedDatasetEntityStruct|null $first */
        $first = $mappedEntities->first();
        /** @var MappedDatasetEntityStruct|null $last */
        $last = $mappedEntities->last();

        static::assertNotNull($first);
        static::assertInstanceOf(MappedDatasetEntityStruct::class, $first);
        static::assertNotNull($last);
        static::assertInstanceOf(MappedDatasetEntityStruct::class, $last);
        static::assertSame($first->getDatasetEntity()->getPrimaryKey(), $last->getDatasetEntity()->getPrimaryKey());

        $reflectionMappings = [];

        /** @var MappedDatasetEntityStruct $mappedEntity */
        foreach ($mappedEntities as $mappedEntity) {
            $reflectionMapping = $mappedEntity->getDatasetEntity()->getAttachment(PrimaryKeySharingMappingStruct::class);

            if ($reflectionMapping instanceof PrimaryKeySharingMappingStruct) {
                $reflectionMappings[\spl_object_hash($reflectionMapping)] = \count(\iterable_to_array($reflectionMapping->getOwners()));
            }
        }

        static::assertLessThanOrEqual(\array_sum($reflectionMappings), \count($reflectionMappings));
    }


    /**
     * @param class-string<DatasetEntityContract> $entityClass
     * @dataProvider provideEntityClasses
     */
    public function testReflectToDifferentPortalNodeWithMoreThanTwoMappings(string $entityClass): void
    {
        $facade = $this->createStorageFacade();
        $identityMap = $facade->getIdentityMapAction();
        $identityReflect = $facade->getIdentityReflectAction();
        $identityPersist = $facade->getIdentityPersistAction();

        /** @var DatasetEntityContract $datasetEntity */
        $datasetEntity = new $entityClass();
        $datasetEntity->setPrimaryKey('ec7587bb-0ee0-4b7e-a980-1c258695e011');

        $tracked = new DatasetEntityCollection((new DeepObjectIteratorContract())->iterate($datasetEntity));
        $identityMapResult = $identityMap->map(new IdentityMapPayload($this->portalA, $tracked));
        $identityPersistPayload = new IdentityPersistPayload($this->portalB, new IdentityPersistPayloadCollection());
        $identityPersistThirdPayload = new IdentityPersistPayload($this->portalC, new IdentityPersistPayloadCollection());
        $mappedEntities = new MappedDatasetEntityCollection();
        $mappingPairs = [];

        /** @var MappedDatasetEntityStruct $mappedEntity */
        foreach ($identityMapResult->getMappedDatasetEntityCollection() as $mappedEntity) {
            /** @var DatasetEntityContract $entity */
            $entity = new $entityClass();
            $sourceId = $mappedEntity->getMapping()->getExternalId();
            $targetId = $sourceId . '-other-side';
            $mappingNodeKey = $mappedEntity->getMapping()->getMappingNodeKey();
            $mappingPairs['object_hash'][\spl_object_hash($entity)] = $targetId;
            $mappingPairs['reflection_mapping'][$sourceId] = $targetId;

            $entity->setPrimaryKey($sourceId);
            $mappedEntities->push([
                new MappedDatasetEntityStruct(
                    new Mapping($sourceId, $this->portalA, $mappingNodeKey, $entityClass),
                    $entity
                )
            ]);

            $identityPersistPayload->getMappingPersistPayloads()->push([
                new IdentityPersistCreatePayload($mappingNodeKey, $targetId)
            ]);
            $identityPersistThirdPayload->getMappingPersistPayloads()->push([
                new IdentityPersistCreatePayload($mappingNodeKey, '587e5d0b-b5e2-4ad2-b088-fcc6f7d70d6f')
            ]);
        }

        $identityPersist->persist($identityPersistPayload);
        $identityPersist->persist($identityPersistThirdPayload);
        $identityReflect->reflect(new IdentityReflectPayload($this->portalB, $mappedEntities));

        /** @var MappedDatasetEntityStruct $mappedEntity */
        foreach ($mappedEntities as $mappedEntity) {
            $entity = $mappedEntity->getDatasetEntity();
            /** @var PrimaryKeySharingMappingStruct $reflectionMapping */
            $reflectionMapping = $entity->getAttachment(PrimaryKeySharingMappingStruct::class);

            static::assertSame($mappingPairs['object_hash'][\spl_object_hash($entity)], $entity->getPrimaryKey());
            static::assertSame($mappingPairs['reflection_mapping'][$reflectionMapping->getExternalId()], $entity->getPrimaryKey());
        }
    }

    /**
     * @param class-string<DatasetEntityContract> $entityClass
     * @dataProvider provideEntityClasses
     */
    public function testReflectToDifferentPortalNodeWithNoMappingsYet(string $entityClass): void
    {
        $facade = $this->createStorageFacade();
        $identityMap = $facade->getIdentityMapAction();
        $identityReflect = $facade->getIdentityReflectAction();

        /** @var DatasetEntityContract $datasetEntity */
        $datasetEntity = new $entityClass();
        $datasetEntity->setPrimaryKey('ce909bf3-6564-47dd-ad81-c3a94bc1aad0');

        $tracked = new DatasetEntityCollection((new DeepObjectIteratorContract())->iterate($datasetEntity));
        $identityMapResult = $identityMap->map(new IdentityMapPayload($this->portalA, $tracked));
        $mappedEntities = new MappedDatasetEntityCollection();

        /** @var MappedDatasetEntityStruct $mappedEntity */
        foreach ($identityMapResult->getMappedDatasetEntityCollection() as $mappedEntity) {
            $sourceId = $mappedEntity->getMapping()->getExternalId();
            $mappingNodeKey = $mappedEntity->getMapping()->getMappingNodeKey();

            /** @var DatasetEntityContract $entity */
            $entity = new $entityClass();

            $entity->setPrimaryKey($sourceId);
            $mappedEntities->push([
                new MappedDatasetEntityStruct(
                    new Mapping($sourceId, $this->portalA, $mappingNodeKey, $entityClass),
                    $entity
                )
            ]);
        }

        $identityReflect->reflect(new IdentityReflectPayload($this->portalB, $mappedEntities));

        /** @var MappedDatasetEntityStruct $mappedEntity */
        foreach ($mappedEntities as $mappedEntity) {
            static::assertNull($mappedEntity->getDatasetEntity()->getPrimaryKey());
        }
    }

    public function provideEntityClasses(): iterable
    {
        yield [EntityA::class];
        yield [EntityB::class];
        yield [EntityC::class];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $facade = $this->createStorageFacade();
        $portalNodeCreate = $facade->getPortalNodeCreateAction();
        $portalNodeGet = $facade->getPortalNodeGetAction();
        $createPayloads = new PortalNodeCreatePayloads([
            new PortalNodeCreatePayload(PortalA::class),
            new PortalNodeCreatePayload(PortalB::class),
            new PortalNodeCreatePayload(PortalC::class),
        ]);
        $createResults = $portalNodeCreate->create($createPayloads);
        $getCriteria = new PortalNodeGetCriteria(new PortalNodeKeyCollection($createResults->column('getPortalNodeKey')));

        foreach ($portalNodeGet->get($getCriteria) as $portalNode) {
            if ($portalNode->getPortalClass() === PortalA::class) {
                $this->portalA = $portalNode->getPortalNodeKey();
            }

            if ($portalNode->getPortalClass() === PortalB::class) {
                $this->portalB = $portalNode->getPortalNodeKey();
            }

            if ($portalNode->getPortalClass() === PortalC::class) {
                $this->portalC = $portalNode->getPortalNodeKey();
            }
        }

        static::assertSame($createPayloads->count(), $createResults->count());
        static::assertNotNull($this->portalA);
        static::assertNotNull($this->portalB);
        static::assertNotNull($this->portalC);
    }

    protected function tearDown(): void
    {
        $facade = $this->createStorageFacade();
        $portalNodeDelete = $facade->getPortalNodeDeleteAction();

        $portalNodeDelete->delete(new PortalNodeDeleteCriteria(new PortalNodeKeyCollection([
            $this->portalA,
            $this->portalB,
            $this->portalC,
        ])));
        $this->portalA = null;
        $this->portalB = null;
        $this->portalC = null;

        parent::tearDown();
    }

    abstract protected function createStorageFacade(): StorageFacadeInterface;
}
