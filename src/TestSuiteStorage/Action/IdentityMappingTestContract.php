<?php

declare(strict_types=1);

namespace Heptacom\HeptaConnect\TestSuite\Storage\Action;

use Heptacom\HeptaConnect\Dataset\Base\Contract\DatasetEntityContract;
use Heptacom\HeptaConnect\Dataset\Base\DatasetEntityCollection;
use Heptacom\HeptaConnect\Portal\Base\Mapping\MappedDatasetEntityStruct;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\Contract\PortalNodeKeyInterface;
use Heptacom\HeptaConnect\Portal\Base\StorageKey\PortalNodeKeyCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Map\IdentityMapResult;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist\IdentityPersistCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist\IdentityPersistPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Persist\IdentityPersistPayloadCollection;
use Heptacom\HeptaConnect\Storage\Base\Action\Identity\Reflect\IdentityReflectPayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayload;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Create\PortalNodeCreatePayloads;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Delete\PortalNodeDeleteCriteria;
use Heptacom\HeptaConnect\Storage\Base\Action\PortalNode\Get\PortalNodeGetCriteria;
use Heptacom\HeptaConnect\Storage\Base\Bridge\Contract\StorageFacadeInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityMapActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityPersistActionInterface;
use Heptacom\HeptaConnect\Storage\Base\Contract\Action\Identity\IdentityReflectActionInterface;
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

    private ?IdentityMapActionInterface $identityMap = null;

    private ?IdentityReflectActionInterface $identityReflect = null;

    private ?IdentityPersistActionInterface $identityPersist = null;

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

        static::assertInstanceOf(MappedDatasetEntityStruct::class, $firstEntity);
        static::assertEquals($entityA->getPrimaryKey(), $firstEntity->getMapping()->getExternalId());

        static::assertInstanceOf(MappedDatasetEntityStruct::class, $secondEntity);
        static::assertEquals($entityA->getPrimaryKey(), $secondEntity->getMapping()->getExternalId());

        static::assertNotSame($firstEntity, $secondEntity);
    }

    /**
     * @param class-string<DatasetEntityContract> $entityClass
     * @dataProvider provideEntityClasses
     */
    public function testReflectFromPortalNodeAToB(string $entityClass): void
    {
        $sourceId = 'e9011418-5535-4180-93e9-94b44cc3e28d';
        $targetId = $sourceId . '-other-side';

        /** @var DatasetEntityContract $datasetEntity */
        $datasetEntity = new $entityClass();
        $datasetEntity->setPrimaryKey($sourceId);

        // create new mapping node + identity for portal node A
        $identityMapResult = $this->identifyEntities($this->portalA, new DatasetEntityCollection([$datasetEntity]));
        $mappedEntities = $identityMapResult->getMappedDatasetEntityCollection();

        self::assertCount(1, $mappedEntities);

        /** @var MappedDatasetEntityStruct $mappedEntity */
        $mappedEntity = $mappedEntities->first();
        $identifiedEntity = $mappedEntity->getDatasetEntity();
        $mappingNodeKey = $mappedEntity->getMapping()->getMappingNodeKey();

        // add identity for portal node B to mapping node
        $this->persistIdentity($this->portalB, new IdentityPersistPayloadCollection([
            new IdentityPersistCreatePayload($mappingNodeKey, $targetId)
        ]));

        // reflect entities (this is what we test here)
        $this->identityReflect->reflect(new IdentityReflectPayload($this->portalB, $mappedEntities));

        /** @var MappedDatasetEntityStruct $reflectedMappedEntity */
        foreach ($mappedEntities as $reflectedMappedEntity) {
            $reflectedEntity = $reflectedMappedEntity->getDatasetEntity();

            /** @var PrimaryKeySharingMappingStruct|null $reflectionMapping */
            $reflectionMapping = $reflectedEntity->getAttachment(PrimaryKeySharingMappingStruct::class);

            static::assertInstanceOf(PrimaryKeySharingMappingStruct::class, $reflectionMapping);
            static::assertSame($reflectedEntity, $identifiedEntity);
            static::assertSame($reflectionMapping->getExternalId(), $sourceId);
            static::assertSame($reflectedEntity->getPrimaryKey(), $targetId);
        }
    }

    /**
     * @param class-string<DatasetEntityContract> $entityClass
     * @dataProvider provideEntityClasses
     */
    public function testReflectTwoEntitiesOfSameTypeFromPortalNodeAToB(string $entityClass): void
    {
        $sourceId1 = 'c1870a69-e409-4dbc-be22-d7ac1071cb0c';
        $sourceId2 = 'b2a646e0-64a8-40fc-a784-ec56f8e97054';
        $targetId1 = $sourceId1 . '-other-side';
        $targetId2 = $sourceId2 . '-other-side';

        $datasetEntity1 = new $entityClass();
        $datasetEntity1->setPrimaryKey($sourceId1);
        $datasetEntity2 = new $entityClass();
        $datasetEntity2->setPrimaryKey($sourceId2);
        $datasetEntity1->attach($datasetEntity2);

        // create new mapping nodes + identities for portal node A
        $identityMapResult = $this->identifyEntities($this->portalA, new DatasetEntityCollection([
            $datasetEntity1,
            $datasetEntity2,
        ]));

        $mappedEntities = $identityMapResult->getMappedDatasetEntityCollection();
        $identityPersistPayloadCollection = new IdentityPersistPayloadCollection();

        self::assertCount(2, $mappedEntities);
        $switchCases = [];

        /** @var MappedDatasetEntityStruct $mappedEntity */
        foreach ($mappedEntities as $mappedEntity) {
            $identifiedEntity = $mappedEntity->getDatasetEntity();
            $mappingNodeKey = $mappedEntity->getMapping()->getMappingNodeKey();

            switch ($identifiedEntity->getPrimaryKey()) {
                case $sourceId1:
                    $targetId = $targetId1;
                    $switchCases[0] = true;

                    break;
                case $sourceId2:
                    $targetId = $targetId2;
                    $switchCases[1] = true;

                    break;
                default:
                    self::fail('Entity was not identified correctly.');
            }

            $identityPersistPayloadCollection->push([
                new IdentityPersistCreatePayload($mappingNodeKey, $targetId),
            ]);
        }

        self::assertCount(2, $identityPersistPayloadCollection);
        self::assertCount(2, $switchCases);

        $this->persistIdentity($this->portalB, $identityPersistPayloadCollection);

        // this is what we test here
        $this->identityReflect->reflect(new IdentityReflectPayload($this->portalB, $mappedEntities));

        $mappedEntity1 = null;
        $mappedEntity2 = null;
        $switchCases = [];

        /** @var MappedDatasetEntityStruct $mappedEntity */
        foreach ($mappedEntities as $mappedEntity) {
            $identifiedEntity = $mappedEntity->getDatasetEntity();

            switch ($identifiedEntity->getPrimaryKey()) {
                case $targetId1:
                    $mappedEntity1 = $mappedEntity;
                    $switchCases[0] = true;

                    break;
                case $targetId2:
                    $mappedEntity2 = $mappedEntity;
                    $switchCases[1] = true;

                    break;
                default:
                    self::fail('Entity was not identified correctly.');
            }
        }

        static::assertCount(2, $switchCases);
        static::assertInstanceOf(MappedDatasetEntityStruct::class, $mappedEntity1);
        static::assertInstanceOf(MappedDatasetEntityStruct::class, $mappedEntity2);
        static::assertNotSame($mappedEntity1->getDatasetEntity(), $mappedEntity2->getDatasetEntity());

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
    public function testReflectEntityFromPortalNodeAToBAndAlsoExistsInC(string $entityClass): void
    {
        $sourceId = 'ec7587bb-0ee0-4b7e-a980-1c258695e011';
        $targetId = $sourceId . '-other-side';

        /** @var DatasetEntityContract $datasetEntity */
        $datasetEntity = new $entityClass();
        $datasetEntity->setPrimaryKey($sourceId);

        // create new mapping node + identity for portal node A
        $identityMapResult = $this->identifyEntities($this->portalA, new DatasetEntityCollection([$datasetEntity]));
        $mappedEntities = $identityMapResult->getMappedDatasetEntityCollection();

        /** @var MappedDatasetEntityStruct $mappedEntity */
        $mappedEntity = $mappedEntities->first();
        $identifiedEntity = $mappedEntity->getDatasetEntity();
        $mappingNodeKey = $mappedEntity->getMapping()->getMappingNodeKey();

        // add identity for portal node B to mapping node
        $this->persistIdentity($this->portalB, new IdentityPersistPayloadCollection([
            new IdentityPersistCreatePayload($mappingNodeKey, $targetId)
        ]));

        // add identity for portal C to mapping node
        $this->persistIdentity($this->portalC, new IdentityPersistPayloadCollection([
            new IdentityPersistCreatePayload($mappingNodeKey, '587e5d0b-b5e2-4ad2-b088-fcc6f7d70d6f')
        ]));

        $this->identityReflect->reflect(new IdentityReflectPayload($this->portalB, $mappedEntities));

        /** @var PrimaryKeySharingMappingStruct $reflectionMapping */
        $reflectionMapping = $identifiedEntity->getAttachment(PrimaryKeySharingMappingStruct::class);

        static::assertInstanceOf(PrimaryKeySharingMappingStruct::class, $reflectionMapping);
        static::assertSame($sourceId, $reflectionMapping->getExternalId());
        static::assertSame($targetId, $identifiedEntity->getPrimaryKey());
    }

    /**
     * @param class-string<DatasetEntityContract> $entityClass
     * @dataProvider provideEntityClasses
     */
    public function testReflectEntityFromPortalNodeAToBButItIsNewInB(string $entityClass): void
    {
        $sourceId = 'ce909bf3-6564-47dd-ad81-c3a94bc1aad0';

        /** @var DatasetEntityContract $datasetEntity */
        $datasetEntity = new $entityClass();
        $datasetEntity->setPrimaryKey($sourceId);

        // create new mapping node + identity for portal node A
        $identityMapResult = $this->identifyEntities($this->portalA, new DatasetEntityCollection([$datasetEntity]));
        $mappedEntities = $identityMapResult->getMappedDatasetEntityCollection();

        /** @var MappedDatasetEntityStruct $mappedEntity */
        $mappedEntity = $mappedEntities->first();
        $identifiedEntity = $mappedEntity->getDatasetEntity();

        // this is what we test
        $this->identityReflect->reflect(new IdentityReflectPayload($this->portalB, $mappedEntities));

        static::assertNull($identifiedEntity->getPrimaryKey());
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
        $this->identityMap = $facade->getIdentityMapAction();
        $this->identityReflect = $facade->getIdentityReflectAction();
        $this->identityPersist = $facade->getIdentityPersistAction();

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

    private function identifyEntities(
        PortalNodeKeyInterface $portal,
        DatasetEntityCollection $datasetEntityCollection
    ): IdentityMapResult {
        return $this->identityMap->map(
            new IdentityMapPayload($portal, $datasetEntityCollection)
        );
    }

    private function persistIdentity(
        PortalNodeKeyInterface $portal,
        IdentityPersistPayloadCollection $identityPersistPayloadCollection
    ): void {
        $this->identityPersist->persist(new IdentityPersistPayload($portal, $identityPersistPayloadCollection));
    }
}
